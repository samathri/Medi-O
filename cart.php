<?php
session_start();
include 'includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for user
$sql = "
    SELECT c.id AS cart_id, c.quantity, p.name, p.price, p.image_path
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($item = $result->fetch_assoc()) {
    $cartItems[] = $item;
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Cart - Medi-O</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
<?php include("components/header.php"); ?>

<section class="cart-page py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold text-primary">Your Cart</h2>

        <div class="row">
            <!-- Left Column: Cart Items -->
            <div class="col-lg-8">
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item d-flex justify-content-between align-items-center mb-4 p-3 border rounded shadow-sm">
                            <div class="cart-item-info d-flex align-items-center">
                                <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img me-3" style="width: 80px; height: auto;">
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                                    <p class="mb-0 text-muted">Rs. <?= number_format($item['price'], 2) ?></p>
                                </div>
                            </div>
                            <div class="cart-item-actions d-flex align-items-center">
                                <input type="number" class="form-control cart-item-quantity" value="<?= $item['quantity'] ?>" min="1" max="10" style="width: 60px;">
                                <form method="POST" action="remove-from-cart.php" class="ms-3">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                    <button class="btn btn-outline-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No items in your cart.</p>
                <?php endif; ?>
            </div>

            <!-- Right Column: Summary -->
            <div class="col-lg-4">
                <div class="cart-summary p-4 border rounded shadow-sm">
                    <h4 class="fw-bold text-primary mb-4">Cart Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span>Rs. <?= number_format($total, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>Rs. 150.00</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span>Total:</span>
                        <span>Rs. <?= number_format($total + 150, 2) ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary w-100 mb-2">Checkout</a>
                    <a href="shop.php" class="btn btn-outline-primary w-100">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("components/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
