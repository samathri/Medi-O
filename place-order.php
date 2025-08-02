<?php
session_start();
include 'includes/db.php';

// Load QR code library
require_once 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $cart = $_SESSION['cart'] ?? [];

    if (!$user_id || empty($cart)) {
        die('Cart is empty or user not logged in.');
    }

    // Fetch customer name from users table
    $stmtUser = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $stmtUser->bind_result($customerName);
    $stmtUser->fetch();
    $stmtUser->close();

    // Collect billing form data
    $name = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal = $_POST['postalCode'] ?? '';
    $country = $_POST['country'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';

    // Store billing info in session
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
    $order_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $order_date, $total, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    // Generate QR content including customer name from users table
    $qrContent = "Order ID: $order_id\n"
               . "Customer: $customerName\n"
               . "Date: $order_date\n"
               . "Total: Rs. $total\n"
               . "Status: $status";

    // Generate QR code image
    $qrResult = Builder::create()
        ->data($qrContent)
        ->size(300)
        ->margin(10)
        ->build();

    // Convert QR code to base64 string for storage
    $qrBase64 = base64_encode($qrResult->getString());

    // Update order with QR code image string
    $stmt = $conn->prepare("UPDATE orders SET qr_code = ? WHERE order_id = ?");
    $stmt->bind_param("si", $qrBase64, $order_id);
    $stmt->execute();
    $stmt->close();

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: successful.php?order_id=" . $order_id);
    exit;
} else {
    header("Location: checkout.php");
    exit;
}
