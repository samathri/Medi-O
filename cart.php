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
    <header class="medi-o-header-container">
        <nav class="medi-o-navbar navbar navbar-expand-lg">
            <div class="container-fluid medi-o-container-fluid">

                <!-- Logo (left) -->
                <a class="medi-o-navbar-brand navbar-brand" href="home.php">
                    <img src="images/medi-o-logo.svg" alt="Medi-O Logo" class="medi-o-logo logo-h" />
                </a>

                <!-- Hamburger button (right) -->
                <button class="medi-o-navbar-toggler navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#medi-o-navbarMenu" aria-controls="medi-o-navbarMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="medi-o-navbar-toggler-icon navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="medi-o-navbar-collapse collapse navbar-collapse" id="medi-o-navbarMenu">
                    <div class="w-100 d-lg-flex flex-lg-column align-items-lg-end medi-o-navbar-collapse-inner">

                        <!-- Nav Links -->
                        <ul class="medi-o-navbar-nav navbar-nav flex-lg-row mb-2 gap-3 mb-lg-0">
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="home.php">Home</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="shop.php">Shop</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="about.php">About
                                    Us</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="contact.php">Contact
                                    Us</a></li>
                        </ul>

                        <!-- Desktop Icons -->
                        <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
                            <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                            <a href="cart.php" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>

                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                                <div class="dropdown">
                                    <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none" href="#"
                                        id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i
                                            class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>

                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                            <?php endif; ?>
                        </div>


                        <div class="medi-o-nav-icons-mobile nav-icons d-lg-none mt-3">
                            <a href="#" class="medi-o-nav-icon-link nav-m"><i class="bi bi-search"></i></a>
                            <a href="cart.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-cart"></i></a>

                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                                <div class="dropdown">
                                    <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none nav-m" href="#"
                                        id="userDropdownMobile" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i
                                            class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>

                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownMobile">
                                        <li><a class="dropdown-item nav-m" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item text-danger nav-m" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-person"></i></a>
                            <?php endif; ?>
                        </div>


                    </div>
                </div>

                <!-- Search Popup Overlay -->
                <div class="medi-o-search-overlay d-none" id="searchOverlay">
                    <div class="medi-o-search-popup" role="dialog" aria-label="Search popup">
                        <!-- Close Button (aligned to the top-right corner) -->
                        <button type="button" class="btn medi-o-search-close" aria-label="Close search popup"
                            onclick="toggleSearchPopup(false)">
                            &times;
                        </button>

                        <form class="medi-o-search-form-popup position-relative" role="search"
                            aria-label="Popup site search">
                            <input type="search" id="medi-o-search-input-popup"
                                class="form-control medi-o-search-input-popup" placeholder="Search Here....."
                                aria-labelledby="medi-o-search-label-popup" autocomplete="off" />
                            <label id="medi-o-search-label-popup" for="medi-o-search-input-popup"
                                class="visually-hidden">Search</label>

                            <button type="submit" class="btn medi-o-search-btn-popup" aria-label="Submit popup search">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>



            </div>
        </nav>
    </header>




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



    <!-- Footer Section -->
    <footer class="medi-o-footer text-white py-5" style="background-color: #0467BB;">
        <div class="container medi-o-container">
            <div class="row gy-4 text-center text-md-start">
                <!-- Logo & Description -->
                <div class="col-md-6">
                    <a href="home.php"><img src="images/medi-o-logo-white.svg" alt="Medi-O Logo logo-f" height="60"
                            class="medi-o-footer-logo mb-3 logo-f" /></a>
                    <p class="medi-o-footer-description mb-0">
                        Medi-O is a licensed and registered pharmacy compliant with NMRA (National Medicine Regulatory
                        Authority).<br />
                        Registration No: <strong>(NMRA/R/5255/2022)</strong>.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-3">
                    <h5 class="fw-semibold mb-4">Quick Links</h5>
                    <ul class="list-unstyled medi-o-footer-links">
                        <li><a href="faq.php" class="text-white text-decoration-none d-block mb-2">FAQs</a></li>
                        <li><a href="about.php" class="text-white text-decoration-none d-block mb-2">About Us</a></li>
                        <li><a href="contact.php" class="text-white text-decoration-none d-block mb-2">Contact Us</a>
                        </li>
                        <li><a href="terms.php" class="text-white text-decoration-none d-block mb-2">Terms &
                                Conditions</a></li>
                        <li><a href="privacy.php" class="text-white text-decoration-none d-block">Privacy Policy</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-3">
                    <h5 class="fw-semibold mb-4 ">Contact Us</h5>
                    <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i
                                class="bi bi-geo-alt-fill me-2"></i>Colombo, Sri Lanka</a></p>
                    <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i
                                class="bi bi-envelope-fill me-2"></i>info@medio.com</a></p>
                    <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i
                                class="bi bi-telephone-fill me-2"></i>+94 77 123 4567</i></a></p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/script.js"></script>



</body>

</html>