<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $cart = $_SESSION['cart'] ?? [];

    if (!$user_id || empty($cart)) {
<<<<<<< HEAD
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
=======
        die('Cart is empty or user not logged in.');
    }

    // Safely fetch billing form data
    $name = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal = $_POST['postalCode'] ?? '';
    $country = $_POST['country'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';

    // Store billing info in session for later use
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
    $_SESSION['billing_name'] = $name;
    $_SESSION['billing_email'] = $email;
    $_SESSION['billing_phone'] = $phone;
    $_SESSION['billing_address'] = "$address, $city, $postal, $country";

<<<<<<< HEAD
    // Calculate total
=======
    // Calculate total order amount
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

<<<<<<< HEAD
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
=======
    // Insert into orders table
    $order_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $order_date, $total, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
<<<<<<< HEAD

    // Clear cart
    unset($_SESSION['cart']);

    // ✅ Redirect to updated success page
    header("Location: successful.php?order_id=" . $order_id);
    exit;
} else {
=======
    $stmt->close();

    // Clear cart session
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: successful.php?order_id=" . $order_id);
    exit;

} else {
    // Redirect to checkout page if not a POST request
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
    header("Location: checkout.php");
    exit;
}
