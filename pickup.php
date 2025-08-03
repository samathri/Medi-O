<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$user_id = $_SESSION['user_id'];

// Fetch order, user and prescription details for logged-in user
$sql = "SELECT o.id AS order_id, o.total_amount, u.name, u.phone, p.id AS prescription_id, p.picked_up 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN prescriptions_2 p ON p.order_id = o.id
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No orders found for this user.');
}

$order = $result->fetch_assoc();

// Handle confirm pickup button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_pickup'])) {
    $update = $conn->prepare("UPDATE prescriptions_2 SET picked_up = 1 WHERE id = ?");
    $update->bind_param('i', $order['prescription_id']);
    $update->execute();

    echo "<script>alert('Pickup confirmed!'); window.location.href='pickup.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Order Pickup Confirmation</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Order Pickup Confirmation</h2>
    <div class="card p-4">
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($order['phone']) ?></p>
        <p><strong>Total Amount:</strong> Rs. <?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Picked Up:</strong> <?= $order['picked_up'] ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ?></p>

        <?php if (!$order['picked_up']): ?>
        <form method="POST">
            <button type="submit" name="confirm_pickup" class="btn btn-primary">Confirm Pickup</button>
        </form>
        <?php else: ?>
        <div class="alert alert-success mt-3">This order has already been picked up.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
