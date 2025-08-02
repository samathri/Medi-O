<?php
session_start();
include 'includes/db.php';

// Check if order_id is passed
if (!isset($_GET['order_id'])) {
    echo "<div class='alert alert-danger'>No order ID found.</div>";
    exit;
}

$order_id = intval($_GET['order_id']);

// Get order details
$stmt = $conn->prepare("SELECT o.order_id, o.order_date, o.total_amount, o.status, u.name AS customer_name
                        FROM orders o
                        JOIN users u ON o.user_id = u.id
                        WHERE o.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Order not found.</div>";
    exit;
}

$order = $result->fetch_assoc();
$stmt->close();

// Get order items
$stmt = $conn->prepare("SELECT oi.quantity, oi.price, p.name AS product_name
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success - Medi-O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="bg-white p-4 rounded shadow-sm">
        <h2 class="mb-4 text-success">🎉 Order Successful!</h2>

        <h5>Order Summary</h5>
        <ul class="list-unstyled">
            <li><strong>Order ID:</strong> <?= $order['order_id'] ?></li>
            <li><strong>Date:</strong> <?= $order['order_date'] ?></li>
            <li><strong>Status:</strong> <?= $order['status'] ?></li>
            <li><strong>Total:</strong> Rs. <?= number_format($order['total_amount'], 2) ?></li>
            <li><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></li>
        </ul>

        <h5 class="mt-4">Billing Information</h5>
        <ul class="list-unstyled">
            <li><strong>Name:</strong> <?= $_SESSION['billing_name'] ?? 'N/A' ?></li>
            <li><strong>Email:</strong> <?= $_SESSION['billing_email'] ?? 'N/A' ?></li>
            <li><strong>Phone:</strong> <?= $_SESSION['billing_phone'] ?? 'N/A' ?></li>
            <li><strong>Address:</strong> <?= $_SESSION['billing_address'] ?? 'N/A' ?></li>
        </ul>

        <h5 class="mt-4">Items Ordered</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-end">Rs. <?= number_format($item['price'], 2) ?></td>
                    <td class="text-end">Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-primary mt-4">Back to Home</a>
    </div>
</div>
</body>
</html>
