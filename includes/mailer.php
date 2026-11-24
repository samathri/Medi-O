<?php
/** Email via PHPMailer. Without SMTP credentials, emails are written to
 *  logs/mail.log so every flow stays testable offline. */

use PHPMailer\PHPMailer\PHPMailer;

/** Preserve action links in both demo logs and plain-text email. */
function mail_plain_text(string $html): string
{
    $withLinks = preg_replace_callback('~<a\b[^>]*href\s*=\s*(["\'])(.*?)\1[^>]*>(.*?)</a>~is',
        fn($m) => strip_tags($m[3]) . ' (' . $m[2] . ')', $html);
    $text = preg_replace('~<(?:br\s*/?|/p|/div|/h[1-6])>~i', "\n", $withLinks);
    return trim(html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function send_mail(string $to, string $subject, string $html): bool
{
    if (SMTP_USER === '' || SMTP_PASS === '') {
        $dir = ROOT_PATH . '/logs';
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        if (is_dir($dir) && is_writable($dir)) file_put_contents(
            $dir . '/mail.log',
            sprintf("[%s] TO:%s | %s\n%s\n%s\n", date('Y-m-d H:i:s'), $to, $subject, mail_plain_text($html), str_repeat('-', 60)),
            FILE_APPEND
        );
        return true; // demo mode
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = mail_plain_text($html);
        return $mail->send();
    } catch (Throwable $e) {
        error_log('Mail error: ' . $e->getMessage());
        return false;
    }
}

/** Simple branded wrapper for transactional emails. */
function mail_template(string $heading, string $bodyHtml, string $ctaText = '', string $ctaUrl = ''): string
{
    $cta = '';
    if ($ctaText && $ctaUrl) {
        $cta = '<p style="margin:24px 0"><a href="' . e($ctaUrl) . '" style="background:#33658a;color:#fff;
            padding:12px 26px;border-radius:8px;text-decoration:none;font-weight:600">' . e($ctaText) . '</a></p>';
    }
    return '<div style="font-family:Segoe UI,Arial,sans-serif;max-width:560px;margin:auto;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
      <div style="background:linear-gradient(135deg,#28506e,#33658a);padding:20px 28px;color:#fff;font-size:20px;font-weight:700">Medi-O Pharmacy</div>
      <div style="padding:28px">
        <h2 style="margin:0 0 12px;color:#0f172a">' . e($heading) . '</h2>
        <div style="color:#334155;font-size:15px;line-height:1.6">' . $bodyHtml . '</div>' . $cta . '
        <p style="color:#94a3b8;font-size:12px;margin-top:28px">' . e(setting('pharmacy_address')) . ' · ' . e(setting('pharmacy_phone')) . '</p>
      </div></div>';
}
