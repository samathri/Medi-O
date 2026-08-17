<?php
/**
 * Medicine reminder dispatcher — run every 5 minutes.
 *
 * Windows Task Scheduler:
 *   Program:  C:\xampp\php\php.exe
 *   Args:     C:\xampp\htdocs\medio\cron\send-reminders.php
 *   Trigger:  Daily, repeat every 5 minutes
 *
 * Or (for demos) trigger it from a browser while logged in as staff:
 *   http://localhost/medio/cron/send-reminders.php
 *
 * For every active medication dose whose time has arrived (within the last
 * 30 min) it sends a WhatsApp reminder (Meta Cloud API — or logs it in demo
 * mode), falls back to email, and records an in-app notification.
 */
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/whatsapp.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

if (PHP_SAPI !== 'cli') {
    // Browser trigger allowed for staff only (handy for demos)
    if (!is_staff()) {
        http_response_code(403);
        exit('Staff only.');
    }
    header('Content-Type: text/plain');
}

// 1) Materialise today's dose log rows for all active medications
foreach (q_all('SELECT t.id AS time_id, t.medication_id, t.time_of_day FROM medication_times t
                JOIN medications m ON m.id = t.medication_id
                WHERE m.active = 1 AND m.start_date <= CURDATE()
                  AND (m.end_date IS NULL OR m.end_date >= CURDATE())') as $slot) {
    q('INSERT IGNORE INTO medication_logs (medication_id, medication_time_id, scheduled_for)
       VALUES (?,?, CONCAT(CURDATE(), " ", ?))', [$slot['medication_id'], $slot['time_id'], $slot['time_of_day']]);
}

// 2) Send reminders for doses due in the last 30 minutes that are still 'scheduled'
$due = q_all("SELECT l.id AS log_id, l.scheduled_for, m.name, m.dosage, m.instructions,
                     u.id AS user_id, u.name AS user_name, u.email, u.phone, u.whatsapp_opt_in
              FROM medication_logs l
              JOIN medications m ON m.id = l.medication_id
              JOIN users u ON u.id = m.user_id
              WHERE l.status = 'scheduled'
                AND l.scheduled_for <= NOW()
                AND l.scheduled_for >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)");

$sent = 0;
foreach ($due as $d) {
    $time = date('g:i a', strtotime($d['scheduled_for']));
    $text = "💊 Medi-O reminder: time to take your {$d['name']}"
        . ($d['dosage'] ? " ({$d['dosage']})" : '')
        . ($d['instructions'] ? " — {$d['instructions']}" : '')
        . ". Scheduled for {$time}. Reply here or open the app to mark it as taken.";

    $channel = null;
    if ($d['phone'] && $d['whatsapp_opt_in']) {
        wa_send((int)$d['user_id'], $d['phone'], $text);
        $channel = wa_configured() ? 'whatsapp' : 'whatsapp(logged)';
    }
    if (!$channel && $d['email']) {
        send_mail($d['email'], '💊 Time to take ' . $d['name'], mail_template(
            'Time for your medicine', '<p>' . e($text) . '</p>', 'Open tracker', url('medications.php')));
        $channel = 'email';
    }

    notify((int)$d['user_id'], 'medication', 'Time to take ' . $d['name'],
        ($d['dosage'] ? $d['dosage'] . ' · ' : '') . $time, url('medications.php'));

    q("UPDATE medication_logs SET status='sent', channel=?, sent_at=NOW() WHERE id=?", [$channel ?? 'in-app', $d['log_id']]);
    $sent++;
}

// 3) Mark yesterday-or-older unanswered doses as skipped (keeps stats honest)
q("UPDATE medication_logs SET status='skipped'
   WHERE status IN ('scheduled','sent') AND scheduled_for < CURDATE()");

echo date('Y-m-d H:i:s') . " — reminders sent: $sent (whatsapp " . (wa_configured() ? 'LIVE' : 'demo/logged') . ")\n";
