<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
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
</head>

<body>


 
<!-- Header -->
<?php include("components/header.php"); ?>

    




    <!-- Checkout Page Section -->
    <section class="checkout-page py-5">
        <div class="container">
            <h2 class="checkout-title text-center mb-4">Checkout</h2>

            <div class="row">
                <!-- Billing Information Section -->
                <div class="col-lg-7">
                    <div class="billing-info p-4 border rounded">
                        <h4 class="billing-info-title mb-3">Billing Information</h4>
                        <form id="billingForm">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" placeholder="Enter your full name"
                                    required />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email"
                                    placeholder="Enter your email address" required />
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" placeholder="Enter your phone number"
                                    required />
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Shipping Address</label>
                                <textarea class="form-control" id="address" rows="3"
                                    placeholder="Enter your shipping address" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" placeholder="Enter your city"
                                    required />
                            </div>
                            <div class="mb-3">
                                <label for="postalCode" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postalCode"
                                    placeholder="Enter your postal code" required />
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" placeholder="Enter your country"
                                    required />
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary Section -->
                <div class="col-lg-5">
                    <div class="order-summary p-4 border rounded">
                        <h4 class="order-summary-title mb-3">Order Summary</h4>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="mb-0">Subtotal:</p>
                            <p class="mb-0">Rs. 5,110.00</p>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="mb-0">Shipping:</p>
                            <p class="mb-0">Rs. 150.00</p>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <p class="mb-0">Total:</p>
                            <p class="mb-0">Rs. 5,260.00</p>
                        </div>

                        <!-- Payment Method Section -->
                        <h5 class="payment-method-title">Payment Method</h5>
                        <div class="mb-3">
                            <select class="form-select" id="paymentMethod" aria-label="Payment Method"
                                onchange="showPaymentDetails()">
                                <option selected>Choose Payment Method</option>
                                <option value="creditCard">Credit/Debit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <!-- Payment Details (Credit/Debit Card, PayPal) -->
                        <div id="creditCardDetails" class="payment-details d-none">
                            <h6 class="mb-3">Credit/Debit Card Details</h6>
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Card Number</label>
                                <input type="text" class="form-control" id="cardNumber"
                                    placeholder="Enter card number" />
                            </div>
                            <div class="mb-3">
                                <label for="expiryDate" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" />
                            </div>
                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" placeholder="Enter CVV" />
                            </div>
                        </div>

                        <div id="paypalDetails" class="payment-details d-none">
                            <h6 class="mb-3">PayPal Details</h6>
                            <p>You'll be redirected to PayPal to complete the payment.</p>
                        </div>

                        <!-- Buttons: Place Order and Cancel -->
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-secondary w-48" type="button"
                                onclick="cancelOrder()">Cancel</button>
                            <button class="btn btn-primary w-48" type="button" onclick="placeOrder()">Place
                                Order</button>
                        </div>
                    </div>
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



</body>

</html>