<?php
session_start();
require 'includes/db.php';

$user_id = $_SESSION['reset_user_id'] ?? null;
$token   = $_SESSION['reset_token'] ?? null;

if (!$user_id || !$token || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($new) || empty($confirm)) {
    $_SESSION['reset_error'] = 'Please fill out both fields.';
    header("Location: reset-password.php?token=$token");
    exit;
}
if ($new !== $confirm) {
    $_SESSION['reset_error'] = 'Passwords do not match.';
    header("Location: reset-password.php?token=$token");
    exit;
}
if (strlen($new) < 6) {
    $_SESSION['reset_error'] = 'Password must be at least 6 characters.';
    header("Location: reset-password.php?token=$token");
    exit;
}

// Update password
$hashed = password_hash($new, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
$stmt->bind_param("si", $hashed, $user_id);
$stmt->execute();

// Cleanup session
unset($_SESSION['reset_user_id'], $_SESSION['reset_token']);

$_SESSION['login_success'] = 'Password reset successful! You can now log in.';
header('Location: login.php');
exit;
?>
