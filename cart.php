<?php
session_start();
include 'includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

<<<<<<< HEAD
// Fetch cart items for the logged-in user
$sql = "
    SELECT c.id AS cart_id, c.quantity, p.name, p.price, p.image_path
    FROM cart c
    INNER JOIN products p ON c.product_id = p.id
=======
// Fetch cart items for user
$sql = "
    SELECT c.id AS cart_id, c.quantity, p.name, p.price, p.image_path
    FROM cart c
    JOIN products p ON c.product_id = p.id
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
<<<<<<< HEAD

=======
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
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
<<<<<<< HEAD
            <!-- Cart Items -->
=======
            <!-- Left Column: Cart Items -->
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
            <div class="col-lg-8">
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item d-flex justify-content-between align-items-center mb-4 p-3 border rounded shadow-sm">
                            <div class="cart-item-info d-flex align-items-center">
<<<<<<< HEAD
                                <img src="<?= htmlspecialchars($item['image_path'] ?: 'images/placeholder.png') ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img me-3" style="width: 80px; height: auto;">
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                                    <p class="mb-0 text-muted">Rs. <?= number_format($item['price'], 2) ?></p>
                                    <small>Quantity: <?= (int)$item['quantity'] ?></small>
                                </div>
                            </div>
                            <div class="cart-item-actions d-flex align-items-center">
                                <form method="POST" action="remove-from-cart.php" class="ms-3">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Remove</button>
=======
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
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
<<<<<<< HEAD
                    <div class="text-center">
                        <p class="text-muted fs-5">Your cart is empty.</p>
                        <a href="shop.php" class="btn btn-primary mt-3">Shop Now</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cart Summary -->
=======
                    <p>No items in your cart.</p>
                <?php endif; ?>
            </div>

            <!-- Right Column: Summary -->
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
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
<<<<<<< HEAD
                    <?php if (!empty($cartItems)): ?>
                        <a href="checkout.php" class="btn btn-primary w-100 mb-2">Proceed to Checkout</a>
                    <?php endif; ?>
=======
                    <a href="checkout.php" class="btn btn-primary w-100 mb-2">Checkout</a>
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
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
