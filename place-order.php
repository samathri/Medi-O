<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $cart = $_SESSION['cart'] ?? [];

    if (!$user_id || empty($cart)) {
        die('Cart empty or user not logged in.');
    }

    // Collect billing data
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal = $_POST['postalCode'];
    $country = $_POST['country'];
    $payment_method = $_POST['payment_method'];

    // Store billing info in session for success page
    $_SESSION['billing_name'] = $name;
    $_SESSION['billing_email'] = $email;
    $_SESSION['billing_phone'] = $phone;
    $_SESSION['billing_address'] = "$address, $city, $postal, $country";

    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    // Clear cart
    unset($_SESSION['cart']);

    // ✅ Redirect to updated success page
    header("Location: successful.php?order_id=" . $order_id);
    exit;
} else {
    header("Location: checkout.php");
    exit;
}
