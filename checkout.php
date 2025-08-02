<?php
session_start();
// Assuming cart stored as $_SESSION['cart']
// Each item: ['product_id', 'name', 'price', 'quantity']

$cart = $_SESSION['cart'] ?? [];

$subtotal = 0;
$shipping = 150.00;  // fixed for example

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medi-O</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
<?php include("components/header.php"); ?>

<section class="checkout-page py-5">
    <div class="container">
        <h2 class="checkout-title text-center mb-4">Checkout</h2>
        <form id="billingForm" action="place-order.php" method="POST">
            <div class="row">

                <!-- Billing Information (left col) -->
                <div class="col-lg-7">
                    <!-- Your billing form here (same as before) -->
                </div>

                <!-- Order Summary (right col) -->
                <div class="col-lg-5">
                    <div class="order-summary p-4 border rounded">
                        <h4 class="order-summary-title mb-3">Order Summary</h4>

                        <ul class="list-group mb-3">
                            <?php if (empty($cart)): ?>
                                <li class="list-group-item">Your cart is empty.</li>
                            <?php else: ?>
                                <?php foreach ($cart as $item): 
                                    $line_total = $item['price'] * $item['quantity'];
                                    $subtotal += $line_total;
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                                        <br>
                                        Qty: <?= intval($item['quantity']) ?>
                                    </div>
                                    <span>Rs. <?= number_format($line_total, 2) ?></span>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>

                        <div class="d-flex justify-content-between mb-3">
                            <p class="mb-0">Subtotal:</p>
                            <p class="mb-0">Rs. <?= number_format($subtotal, 2) ?></p>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="mb-0">Shipping:</p>
                            <p class="mb-0">Rs. <?= number_format($shipping, 2) ?></p>
                        </div>
                        <div class="d-flex justify-content-between mb-4 fw-bold">
                            <p class="mb-0">Total:</p>
                            <p class="mb-0">Rs. <?= number_format($subtotal + $shipping, 2) ?></p>
                        </div>

                        <!-- Payment and buttons (same as before) -->
                        <h5 class="payment-method-title">Payment Method</h5>
                        <div class="mb-3">
                            <select class="form-select" id="paymentMethod" name="payment_method" onchange="showPaymentDetails()" required>
                                <option value="">Choose Payment Method</option>
                                <option value="creditCard">Credit/Debit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <div id="creditCardDetails" class="payment-details d-none">
                            <h6 class="mb-3">Credit/Debit Card Details</h6>
                            <!-- card inputs -->
                        </div>

                        <div id="paypalDetails" class="payment-details d-none">
                            <h6 class="mb-3">PayPal Details</h6>
                            <p>You'll be redirected to PayPal to complete the payment.</p>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-secondary w-48" type="button" onclick="cancelOrder()">Cancel</button>
                            <button class="btn btn-primary w-48" type="submit">Place Order</button>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

<?php include("components/footer.php"); ?>

<script>
function cancelOrder() {
    window.location.href = "cart.php"; // redirect to cart or homepage
}
function showPaymentDetails() {
    const paymentMethod = document.getElementById("paymentMethod").value;
    document.getElementById("creditCardDetails").classList.add("d-none");
    document.getElementById("paypalDetails").classList.add("d-none");

    if (paymentMethod === "creditCard") {
        document.getElementById("creditCardDetails").classList.remove("d-none");
    } else if (paymentMethod === "paypal") {
        document.getElementById("paypalDetails").classList.remove("d-none");
    }
}
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/script.js"></script>

<script>
function cancelOrder() {
    window.location.href = "cart.php"; // or wherever you want to send them
}

function showPaymentDetails() {
    const paymentMethod = document.getElementById("paymentMethod").value;
    document.getElementById("creditCardDetails").classList.add("d-none");
    document.getElementById("paypalDetails").classList.add("d-none");

    if (paymentMethod === "creditCard") {
        document.getElementById("creditCardDetails").classList.remove("d-none");
    } else if (paymentMethod === "paypal") {
        document.getElementById("paypalDetails").classList.remove("d-none");
    }
}
</script>

</body>
</html>
