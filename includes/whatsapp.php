<?php
/**
 * WhatsApp messaging via the Meta Cloud API (free tier).
 * Without credentials, every message is stored in whatsapp_outbox with
 * status 'logged' so the pharmacy can still see, track, and send them
 * manually through the wa.me click-to-chat link shown in the dashboard.
 */

function wa_configured(): bool
{
    return WHATSAPP_TOKEN !== '' && WHATSAPP_PHONE_ID !== '';
}

/**
 * Live health check against the Cloud API (cached per request).
 * Returns [ok(bool), message(string)] — catches expired tokens, which
 * otherwise fail silently on every send.
 */
function wa_health(): array
{
    static $result = null;
    if ($result !== null) return $result;
    if (!wa_configured()) return $result = [false, 'Not configured (demo mode)'];

    $ch = curl_init('https://graph.facebook.com/v20.0/' . WHATSAPP_PHONE_ID . '?fields=display_phone_number');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . WHATSAPP_TOKEN],
        CURLOPT_TIMEOUT => 8,
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200) {
        $d = json_decode((string)$resp, true);
        return $result = [true, 'Connected — sending as ' . ($d['display_phone_number'] ?? 'test number')];
    }
    if ($code === 401 || str_contains((string)$resp, 'expired')) {
        return $result = [false, 'ACCESS TOKEN EXPIRED — copy a fresh one from Meta (Step 1. Try it out) into config.local.php'];
    }
    return $result = [false, 'Cloud API unreachable (HTTP ' . $code . ')'];
}

/** Queue + attempt to send. Returns the outbox row id. */
function wa_send(?int $userId, string $phone, string $message): int
{
    q('INSERT INTO whatsapp_outbox (user_id, phone, message, status) VALUES (?,?,?,?)',
        [$userId, $phone, $message, 'queued']);
    $id = (int)db()->lastInsertId();
    wa_dispatch($id);
    return $id;
}

/**
 * Send an OTP via an approved WhatsApp *authentication template*.
 * Templates deliver instantly with no 24-hour session window. Falls back to
 * a plain-text message when no template name is configured.
 */
function wa_send_otp(?int $userId, string $phone, string $code): int
{
    if (WHATSAPP_OTP_TEMPLATE === '') {
        return wa_send($userId, $phone,
            "Medi-O: Your verification code is {$code}. It expires in 10 minutes. Never share this code.");
    }

    q('INSERT INTO whatsapp_outbox (user_id, phone, message, status) VALUES (?,?,?,?)',
        [$userId, $phone, "Verification code {$code} (template: " . WHATSAPP_OTP_TEMPLATE . ')', 'queued']);
    $id = (int)db()->lastInsertId();

    if (!wa_configured()) {
        q("UPDATE whatsapp_outbox SET status='logged' WHERE id=?", [$id]);
        return $id;
    }

    // Authentication templates take the code as a body param and on the copy button
    [$ok, $resp] = wa_api_post([
        'messaging_product' => 'whatsapp',
        'to' => $phone,
        'type' => 'template',
        'template' => [
            'name' => WHATSAPP_OTP_TEMPLATE,
            'language' => ['code' => 'en_US'],
            'components' => [
                ['type' => 'body', 'parameters' => [['type' => 'text', 'text' => $code]]],
                ['type' => 'button', 'sub_type' => 'url', 'index' => '0',
                 'parameters' => [['type' => 'text', 'text' => $code]]],
            ],
        ],
    ]);
    q('UPDATE whatsapp_outbox SET status=?, response=?, sent_at=NOW() WHERE id=?',
        [$ok ? 'sent' : 'failed', substr($resp, 0, 2000), $id]);
    return $id;
}

/** Low-level Cloud API call. Returns [ok, rawResponse]. */
function wa_api_post(array $payload): array
{
    $ch = curl_init('https://graph.facebook.com/v20.0/' . WHATSAPP_PHONE_ID . '/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . WHATSAPP_TOKEN,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 15,
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code >= 200 && $code < 300, (string)$resp];
}

/** Try to deliver one outbox row via the Cloud API. */
function wa_dispatch(int $outboxId): bool
{
    $row = q_one('SELECT * FROM whatsapp_outbox WHERE id = ?', [$outboxId]);
    if (!$row || $row['status'] === 'sent') return false;

    if (!wa_configured()) {
        q("UPDATE whatsapp_outbox SET status='logged' WHERE id=?", [$outboxId]);
        return false;
    }

    $ch = curl_init('https://graph.facebook.com/v20.0/' . WHATSAPP_PHONE_ID . '/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . WHATSAPP_TOKEN,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'messaging_product' => 'whatsapp',
            'to' => $row['phone'],
            'type' => 'text',
            'text' => ['body' => $row['message']],
        ]),
        CURLOPT_TIMEOUT => 15,
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $ok = $code >= 200 && $code < 300;
    q('UPDATE whatsapp_outbox SET status=?, response=?, sent_at=NOW() WHERE id=?',
        [$ok ? 'sent' : 'failed', substr((string)$resp, 0, 2000), $outboxId]);
    return $ok;
}

/** wa.me link so staff can open a chat with the customer manually. */
function wa_link(string $phone, string $text = ''): string
{
    return 'https://wa.me/' . preg_replace('/\D+/', '', $phone)
        . ($text !== '' ? '?text=' . rawurlencode($text) : '');
}
