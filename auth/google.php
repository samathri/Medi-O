<?php
/**
 * Google Sign-In callback (Google Identity Services posts an ID token here).
 * The token is verified server-side against Google's tokeninfo endpoint.
 */
require_once __DIR__ . '/../config.php';

if (GOOGLE_CLIENT_ID === '') {
    flash('error', 'Google Sign-In is not configured yet.');
    redirect('login.php');
}

$credential = $_POST['credential'] ?? '';
if ($credential === '') {
    flash('error', 'Google Sign-In failed — no credential received.');
    redirect('login.php');
}

// Verify the ID token with Google
$ch = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($credential));
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$payload = $code === 200 ? json_decode((string)$resp, true) : null;

if (!$payload
    || ($payload['aud'] ?? '') !== GOOGLE_CLIENT_ID
    || !in_array($payload['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true)
    || (int)($payload['exp'] ?? 0) < time()
    || empty($payload['sub'])
) {
    flash('error', 'Google Sign-In verification failed. Please try again.');
    redirect('login.php');
}

$googleId = $payload['sub'];
$email = strtolower($payload['email'] ?? '');
$name = $payload['name'] ?? ($payload['given_name'] ?? 'Google User');
$avatar = $payload['picture'] ?? null;

// 1) Existing Google-linked account
$user = q_one('SELECT * FROM users WHERE google_id = ?', [$googleId]);

// 2) Existing email account → link it to Google
if (!$user && $email !== '') {
    $user = q_one('SELECT * FROM users WHERE email = ?', [$email]);
    if ($user) {
        // Google has already verified this email address
        q('UPDATE users SET google_id = ?, avatar = COALESCE(avatar, ?), email_verified = 1 WHERE id = ?',
            [$googleId, $avatar, $user['id']]);
        $user = q_one('SELECT * FROM users WHERE id = ?', [$user['id']]);
    }
}

// 3) Brand-new account
if (!$user) {
    q('INSERT INTO users (name, email, google_id, avatar, qr_token, email_verified) VALUES (?,?,?,?,?,1)',
        [$name, $email ?: null, $googleId, $avatar, new_token()]);
    $user = q_one('SELECT * FROM users WHERE id = ?', [db()->lastInsertId()]);
    notify((int)$user['id'], 'welcome', 'Welcome to Medi-O!', 'Explore the shop, upload a prescription, or chat with our pharmacists.', url('profile.php'));
}

login_user($user);
flash('success', 'Signed in with Google — welcome, ' . $user['name'] . '!');
redirect(take_after_login(role_home($user)));
