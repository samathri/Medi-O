<?php
/**
 * One-time verification codes for email/phone verification and
 * phone-based password reset. Codes are 6 digits, SHA-256-hashed at rest,
 * valid 10 minutes, max 5 wrong attempts, 60s resend cooldown.
 *
 * Demo mode: when SMTP isn't configured, the code is returned so the page can show it —
 * clearly labelled — keeping the whole flow testable offline.
 */

require_once __DIR__ . '/mailer.php';

const OTP_TTL_MINUTES = 10;
const OTP_MAX_ATTEMPTS = 5;
const OTP_RESEND_SECONDS = 60;

/** Creates (or refuses to re-create too soon) a code. Returns [code|null, error|null]. */
function otp_issue(int $userId, string $channel): array
{
    $existing = q_one('SELECT * FROM verification_codes WHERE user_id=? AND channel=?', [$userId, $channel]);
    if ($existing && time() - strtotime($existing['created_at']) < OTP_RESEND_SECONDS) {
        $wait = OTP_RESEND_SECONDS - (time() - strtotime($existing['created_at']));
        return [null, "Please wait {$wait}s before requesting a new code."];
    }
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    q('REPLACE INTO verification_codes (user_id, channel, code_hash, attempts, expires_at)
       VALUES (?,?,?,0,DATE_ADD(NOW(), INTERVAL ' . OTP_TTL_MINUTES . ' MINUTE))',
        [$userId, $channel, hash('sha256', $code)]);
    return [$code, null];
}

/** Checks a submitted code. Returns [ok, error]. Consumes the code on success. */
function otp_verify(int $userId, string $channel, string $code): array
{
    $row = q_one('SELECT * FROM verification_codes WHERE user_id=? AND channel=?', [$userId, $channel]);
    if (!$row) return [false, 'No code was requested — please send a new one.'];
    if (strtotime($row['expires_at']) < time()) return [false, 'That code has expired — please request a new one.'];
    if ($row['attempts'] >= OTP_MAX_ATTEMPTS) return [false, 'Too many wrong attempts — please request a new code.'];

    if (!hash_equals($row['code_hash'], hash('sha256', trim($code)))) {
        q('UPDATE verification_codes SET attempts = attempts + 1 WHERE id=?', [$row['id']]);
        $left = OTP_MAX_ATTEMPTS - $row['attempts'] - 1;
        return [false, 'Incorrect code.' . ($left > 0 ? " {$left} attempt" . ($left === 1 ? '' : 's') . ' left.' : '')];
    }
    q('DELETE FROM verification_codes WHERE id=?', [$row['id']]);
    return [true, null];
}

/**
 * Issues + emails a verification code.
 * Returns [sent, demoCode, error] — demoCode is set only when SMTP is not
 * configured, so the UI can display it (demo mode).
 */
function otp_send_email(array $user): array
{
    if (empty($user['email'])) return [false, null, 'This account has no email address.'];
    [$code, $err] = otp_issue((int)$user['id'], 'email');
    if (!$code) return [false, null, $err];

    send_mail($user['email'], 'Your Medi-O verification code: ' . $code, mail_template(
        'Verify your email',
        '<p>Your Medi-O email verification code is:</p>
         <p style="font-size:30px;font-weight:800;letter-spacing:8px;color:#28506e">' . $code . '</p>
         <p>It expires in ' . OTP_TTL_MINUTES . ' minutes. If you didn\'t create a Medi-O account, ignore this email.</p>'
    ));
    return [true, SMTP_USER === '' ? $code : null, null];
}

