<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die('Invalid order.');
}
$order_id = intval($_GET['order_id']);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('Not logged in.');
}

// Fetch order
$stmt = $conn->prepare("SELECT order_id, total_amount, status, order_date FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) die('Order not found.');

// Fetch items
$stmt = $conn->prepare("SELECT oi.product_id, oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Assume stored billing info in session or DB, use placeholder
$billing = [
    'name' => $_SESSION['billing_name'] ?? 'John Doe',
    'address' => $_SESSION['billing_address'] ?? '123 Elm Street, Colombo',
    'phone' => $_SESSION['billing_phone'] ?? '+94 77 123 4567',
    'email' => $_SESSION['billing_email'] ?? 'john.doe@example.com'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" /><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Order Success — Medi‑O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("components/header.php"); ?>

<section class="order-success py-5">
  <div class="container">
    <div class="text-center">
      <h2 class="mb-4">Thank You for Your Order!</h2>
      <p class="lead mb-4">Your order has been placed successfully.</p>

      <div class="alert alert-success mb-4">
        <strong>Order Number:</strong> #<?= htmlspecialchars($order['order_id']) ?>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <h5 class="fw-semibold">Order Summary</h5>
          <ul class="list-unstyled">
            <?php foreach ($items as $it): ?>
              <li><?= htmlspecialchars($it['name']) ?> × <?= $it['quantity'] ?> @ Rs. <?= number_format($it['price'],2) ?></li>
            <?php endforeach; ?>
            <li class="mt-2">Subtotal: Rs. <?= number_format($order['total_amount'],2) ?></li>
          </ul>
        </div>
        <div class="col-md-6">
          <h5 class="fw-semibold">Shipping Information</h5>
          <ul class="list-unstyled">
            <li><strong>Name:</strong> <?= htmlspecialchars($billing['name']) ?></li>
            <li><strong>Address:</strong> <?= htmlspecialchars($billing['address']) ?></li>
            <li><strong>Phone:</strong> <?= htmlspecialchars($billing['phone']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($billing['email']) ?></li>
          </ul>
        </div>
      </div>

      <a href="index.php" class="btn btn-primary">Return to Homepage</a>
    </div>
  </div>
</section>

<?php include("components/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
