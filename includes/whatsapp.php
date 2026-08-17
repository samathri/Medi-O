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

/** Queue + attempt to send. Returns the outbox row id. */
function wa_send(?int $userId, string $phone, string $message): int
{
    q('INSERT INTO whatsapp_outbox (user_id, phone, message, status) VALUES (?,?,?,?)',
        [$userId, $phone, $message, 'queued']);
    $id = (int)db()->lastInsertId();
    wa_dispatch($id);
    return $id;
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
