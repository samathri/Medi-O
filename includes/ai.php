<?php
/**
 * AI prescription reader (Google Gemini, free tier).
 * Reads the uploaded prescription image/PDF, extracts each medicine with
 * dosage, times and duration, and matches it against the shop's stock.
 * The result is a DRAFT: a pharmacist always reviews and corrects it
 * before anything is approved. Without an API key the pharmacist simply
 * fills the same table manually.
 */

function ai_configured(): bool
{
    return GEMINI_API_KEY !== '';
}

/**
 * Analyse one prescription and store draft items.
 * Returns [ok(bool), message(string)].
 */
function ai_analyze_prescription(int $rxId): array
{
    if (!ai_configured()) {
        return [false, 'AI is not configured — add GEMINI_API_KEY in config.local.php (free at aistudio.google.com/apikey).'];
    }
    $rx = q_one('SELECT * FROM prescriptions WHERE id = ?', [$rxId]);
    if (!$rx) return [false, 'Prescription not found.'];

    $path = ROOT_PATH . '/' . $rx['file_path'];
    if (!is_file($path)) return [false, 'Prescription file is missing on disk.'];
    $mime = mime_content_type($path) ?: 'image/jpeg';
    if (filesize($path) > 15 * 1024 * 1024) return [false, 'File too large for analysis.'];

    // Give the model the live stock list so it can match products
    $products = q_all('SELECT id, name, stock FROM products WHERE is_active = 1');
    $stockList = implode("\n", array_map(
        fn($p) => $p['id'] . ' | ' . $p['name'] . ' | stock:' . $p['stock'], $products));

    $prompt = <<<PROMPT
You are a pharmacy assistant. Read this prescription document carefully.

Extract EVERY medicine prescribed. For each one, work out:
- the medicine name as written (fix obvious spelling only)
- dosage per intake (e.g. "1 tablet", "2 puffs", "5 ml")
- times of day to take it as 24h HH:MM values (interpret: morning=08:00, noon/afternoon=13:00, evening=19:00, night/bedtime=21:00; "bd/twice daily"=08:00,20:00; "tds/3 times"=08:00,13:00,20:00; "qds/4 times"=06:00,11:00,16:00,21:00; "mane"=08:00; "nocte"=21:00)
- duration in days (e.g. "x 5/7"=5, "1/12"=30, "2 weeks"=14; null if not stated or "continue")
- short instructions (e.g. "after meals", "complete full course")

Then match each medicine to this pharmacy's stock list (id | name | stock).
Match on active ingredient/brand even if strength formatting differs slightly;
use null if no reasonable match exists.

STOCK LIST:
$stockList

Respond with ONLY this JSON structure:
{
 "readable": true/false,
 "summary": "1-2 sentence plain-language summary of the prescription (doctor/date if visible, what it treats)",
 "warnings": ["any concerns: unreadable parts, unusual doses, interactions, controlled drugs"],
 "medicines": [
   {"name": "...", "dosage": "...", "times": ["08:00","20:00"], "duration_days": 7,
    "instructions": "...", "matched_product_id": 123 }
 ]
}
If the document is not a prescription or is unreadable, set readable=false and explain in summary.
PROMPT;

    $payload = [
        'contents' => [[
            'parts' => [
                ['inline_data' => ['mime_type' => $mime, 'data' => base64_encode(file_get_contents($path))]],
                ['text' => $prompt],
            ],
        ]],
        'generationConfig' => [
            'response_mime_type' => 'application/json',
            'temperature' => 0.1,
        ],
    ];

    $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/'
        . rawurlencode(GEMINI_MODEL) . ':generateContent?key=' . rawurlencode(GEMINI_API_KEY));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 90,
    ]);
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($resp === false) return [false, 'Could not reach the AI service: ' . $curlErr];
    $data = json_decode((string)$resp, true);
    if ($http !== 200) {
        $msg = $data['error']['message'] ?? ('HTTP ' . $http);
        return [false, 'AI service error: ' . mb_strimwidth($msg, 0, 200, '…')];
    }

    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    $result = json_decode($text, true);
    if (!is_array($result)) return [false, 'AI returned an unreadable response — try again.'];

    // Build the summary (plain text, shown to the pharmacist)
    $summary = trim((string)($result['summary'] ?? ''));
    $warnings = array_filter(array_map('strval', $result['warnings'] ?? []));
    if ($warnings) {
        $summary .= "\n⚠ " . implode("\n⚠ ", $warnings);
    }
    if (empty($result['readable'])) {
        q('UPDATE prescriptions SET ai_summary=?, ai_analyzed_at=NOW() WHERE id=?',
            ['Could not read this document. ' . $summary, $rxId]);
        return [true, 'AI could not read the document — enter the items manually.'];
    }

    // Replace previous draft items
    q('DELETE FROM prescription_items WHERE prescription_id=?', [$rxId]);
    $validIds = array_column($products, 'stock', 'id');
    $count = 0;
    foreach (($result['medicines'] ?? []) as $m) {
        $name = trim((string)($m['name'] ?? ''));
        if ($name === '') continue;
        $pid = (int)($m['matched_product_id'] ?? 0);
        if (!array_key_exists($pid, $validIds)) $pid = 0;
        $times = array_values(array_filter(
            array_map('strval', (array)($m['times'] ?? [])),
            fn($t) => preg_match('/^\d{2}:\d{2}$/', $t)
        ));
        q('INSERT INTO prescription_items
             (prescription_id, product_id, medicine_name, dosage, times, duration_days, instructions, in_stock)
           VALUES (?,?,?,?,?,?,?,?)', [
            $rxId,
            $pid ?: null,
            mb_strimwidth($name, 0, 250),
            mb_strimwidth(trim((string)($m['dosage'] ?? '')), 0, 115) ?: null,
            $times ? implode(',', array_slice($times, 0, 6)) : null,
            isset($m['duration_days']) && $m['duration_days'] !== null ? max(1, min(365, (int)$m['duration_days'])) : null,
            mb_strimwidth(trim((string)($m['instructions'] ?? '')), 0, 250) ?: null,
            $pid && (int)$validIds[$pid] > 0 ? 1 : 0,
        ]);
        $count++;
    }

    q('UPDATE prescriptions SET ai_summary=?, ai_analyzed_at=NOW() WHERE id=?', [$summary ?: null, $rxId]);
    return [true, "AI extracted {$count} medicine" . ($count === 1 ? '' : 's') . ' — please verify each line.'];
}
