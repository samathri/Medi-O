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

    



    <!-- Cart Page Section -->
    <section class="cart-page py-5">
        <div class="container">
            <h2 class="cart-title text-center mb-4">Your Cart</h2>

            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <!-- Cart Item 1 -->
                    <div class="cart-item d-flex justify-content-between align-items-center mb-4">
                        <div class="cart-item-info d-flex align-items-center">
                            <!-- Product Image -->
                            <img src="images/aknicarelotion 1.png" alt="Product Image" class="cart-item-img" />
                            <div class="cart-item-details ms-3">
                                <h4 class="cart-item-title">Aknicare Cleanser</h4>
                                <p class="cart-item-price">Rs. 2,910.00</p>
                            </div>
                        </div>
                        <!-- Quantity and Remove -->
                        <div class="cart-item-actions d-flex align-items-center">
                            <input type="number" class="form-control cart-item-quantity" value="1" min="1" max="10" />
                            <button class="btn btn-outline-danger ms-3">Remove</button>
                        </div>
                    </div>

                    <!-- Cart Item 2 -->
                    <div class="cart-item d-flex justify-content-between align-items-center mb-4">
                        <div class="cart-item-info d-flex align-items-center">
                            <!-- Product Image -->
                            <img src="images/ACNE-AID 1.png" alt="Product Image" class="cart-item-img" />
                            <div class="cart-item-details ms-3">
                                <h4 class="cart-item-title">Acne-Aid Bar</h4>
                                <p class="cart-item-price">Rs. 2,200.00</p>
                            </div>
                        </div>
                        <!-- Quantity and Remove -->
                        <div class="cart-item-actions d-flex align-items-center">
                            <input type="number" class="form-control cart-item-quantity" value="1" min="1" max="10" />
                            <button class="btn btn-outline-danger ms-3">Remove</button>
                        </div>
                    </div>

                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary p-4 border rounded">
                        <h4 class="cart-summary-title">Cart Summary</h4>
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
                        <!-- Link Buttons -->
                        <a href="checkout.php" class="btn btn-primary w-100 mb-3">Checkout</a>
                        <a href="shop.php" class="btn btn-outline-primary w-100">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Best Selling Section -->

    <section class="medi-o-best-selling-section text-center py-5">
        <div class="container medi-o-container">
            <h2 class="text-primary fw-semibold mb-5">BEST SELLING</h2>

            <!-- Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <!-- Product Card 1 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Isocal-600x600.png" class="bs-img img-fluid mb-3" alt="Ortho Shield">
                            <h6 class="medi-o-product-title">ISOCAL POWDER 425g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Siddhalepa-Balm-50g 1.png" class="bs-img img-fluid mb-3"
                                alt="Siddhalepa Balm">
                            <h6 class="medi-o-product-title">SIDDHALEPA BALM 50G</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/ACNE-AID 1.png" class="bs-img img-fluid mb-3" alt="Acne-Aid Bar">
                            <h6 class="medi-o-product-title">ACNE-AID BAR 100g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
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