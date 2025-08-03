<?php
session_start();
include 'includes/db.php';

if (isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
}

header("Location: cart.php");
exit();
