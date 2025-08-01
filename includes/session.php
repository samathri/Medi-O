<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User not logged in
    header("Location: login.php");
    exit;
}
?>
