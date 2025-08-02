<?php
session_start();
<<<<<<< Updated upstream
=======
include 'includes/db.php';

// Validate order_id
if (!isset($_GET['order_id'])) {
    echo "<div class='alert alert-danger'>No order ID provided.</div>";
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order
$stmt = $conn->prepare("SELECT o.order_id, o.order_date, o.total_amount, o.status, o.qr_code, u.name AS customer_name
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

// Fetch items
$stmt = $conn->prepare("SELECT oi.quantity, oi.price, p.name AS product_name
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
>>>>>>> Stashed changes
?>
<!DOCTYPE html>
<html lang="en">

<head>
<<<<<<< Updated upstream
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medi-O</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css" />
=======
    <meta charset="UTF-8">
    <title>Order Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
>>>>>>> Stashed changes
</head>

<body>



<!-- Header -->
<?php include("components/header.php"); ?>

    

    <!-- Order Success Section -->
    <section class="order-success py-5">
        <div class="container">
            <div class="text-center">
                <h2 class="mb-4">Thank You for Your Order!</h2>
                <p class="lead mb-4">Your order has been successfully placed. We will notify you once it is shipped.</p>

                <!-- Order Number -->
                <div class="alert alert-success mb-4" role="alert">
                    <strong>Order Number: </strong> #ORD1234567
                </div>

                <!-- Order Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-semibold">Order Summary</h5>
                        <ul class="list-unstyled">
                            <li>Subtotal: Rs. 5,110.00</li>
                            <li>Shipping: Rs. 150.00</li>
                            <li><strong>Total: Rs. 5,260.00</strong></li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h5 class="fw-semibold">Shipping Information</h5>
                        <ul class="list-unstyled">
                            <li><strong>Name:</strong> John Doe</li>
                            <li><strong>Address:</strong> 123, Elm Street, Colombo</li>
                            <li><strong>Phone:</strong> +94 77 123 4567</li>
                            <li><strong>Email:</strong> john.doe@example.com</li>
                        </ul>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex justify-content-center gap-4">
                    <a href="index.php" class="btn btn-primary">Return to Homepage</a>
                </div>
            </div>
        </div>
    </section>



   <!-- Footer -->
<?php include("components/footer.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/script.js"></script>

<<<<<<< Updated upstream


=======
        <h5 class="mt-4">Billing Info</h5>
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
                    <th class="text-center">Qty</th>
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

        <?php if (!empty($order['qr_code'])): ?>
            <h5 class="mt-4">QR Code</h5>
            <img src="data:image/png;base64,<?= $order['qr_code'] ?>" alt="Order QR Code" class="border p-2" />
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary mt-4">Back to Home</a>
    </div>
</div>
>>>>>>> Stashed changes
</body>

</html>