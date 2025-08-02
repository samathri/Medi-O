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

    

    <!-- Hero Section -->
    <section class="medi-o-hero hero py-5">
        <div class="container medi-o-container">
            <div class="row medi-o-row align-items-center text-center">
                <!-- Text -->
                <div class="col-lg-6 medi-o-col">
                    <div class="medi-o-hero-content hero-content">
                        <h2 class="medi-o-hero-title hero__title">Medicine at Your Fingertips!</h2>
                        <p class="medi-o-hero-desc hero__desc">Skip the queues and manage your prescriptions online.
                            Medi-O lets you order medicines, upload prescriptions, and receive trusted care from
                            home.</p>
                        <button class="btn btn-primary medi-o-btn-primary hero__btn">Upload Your
                            Prescription</button>
                    </div>
                </div>
                <!-- Image could go here if needed -->
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="medi-o-search-bar search-bar mb-5">
        <div class="container medi-o-container">
            <form class="d-flex justify-content-center position-relative medi-o-search-form" role="search"
                aria-label="Site search">
                <input type="search" id="medi-o-search-input-001" class="form-control medi-o-search-input search-input"
                    placeholder="Search Here....." aria-labelledby="medi-o-search-label-001" />
                <label id="medi-o-search-label-001" for="medi-o-search-input-001" class="visually-hidden">Search</label>

                <button type="submit" id="medi-o-search-btn-001" class="btn medi-o-search-btn search-btn"
                    aria-label="Submit search">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </section>


    <!-- Categories -->
    <section class="medi-o-categories categories mb-5">
        <div class="container medi-o-container">
            <div class="row g-3 justify-content-center medi-o-categories-row">

                <!-- Category 1 -->
                <div class="col-6 col-md-3 medi-o-category-col">
                    <a href="shop.php" class="text-decoration-none">
                        <div class="medi-o-category-card category-card p-4">
                            <img src="images/Adult-Care1-01 1.png" alt="Adult Care Icon"
                                class="medi-o-category-icon-img" />
                            <p class="medi-o-category-text category-text mt-3">Adult Care</p>
                        </div>
                    </a>
                </div>

                <!-- Category 2 -->
                <div class="col-6 col-md-3 medi-o-category-col">
                    <a href="shop.php" class="text-decoration-none">
                        <div class="medi-o-category-card category-card p-4">
                            <img src="images/Personal-Care-01-01-01 1.png" alt="Personal Care Icon"
                                class="medi-o-category-icon-img" />
                            <p class="medi-o-category-text category-text mt-3">Personal Care</p>
                        </div>
                    </a>
                </div>

                <!-- Category 3 -->
                <div class="col-6 col-md-3 medi-o-category-col">
                    <a href="shop.php" class="text-decoration-none">
                        <div class="medi-o-category-card category-card p-4">
                            <img src="images/Diabetic.png" alt="Diabetes Care Icon" class="medi-o-category-icon-img" />
                            <p class="medi-o-category-text category-text mt-3">Diabetes Care</p>
                        </div>
                    </a>
                </div>

                <!-- Category 4 -->
                <div class="col-6 col-md-3 medi-o-category-col">
                    <a href="shop.php" class="text-decoration-none">
                        <div class="medi-o-category-card category-card p-4">
                            <img src="images/baby.png" alt="Baby Care Icon" class="medi-o-category-icon-img" />
                            <p class="medi-o-category-text category-text mt-3">Baby Care</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>


    <!-- About Medi-O Section -->
    <section class="medi-o-about-section text-white text-center py-5">
        <div class="container medi-o-container">
            <h2 class="medi-o-about-title fw-semibold mb-4">About Medi-O</h2>
            <p class="medi-o-about-text mb-3 px-2 px-md-5">
                Medi-O was developed to transform traditional pharmacy services into a secure, digital experience —
                allowing users to order medications, upload prescriptions, and receive verified treatments from
                the comfort of their homes.
            </p>
            <p class="medi-o-about-text mb-4 px-2 px-md-5">
                With a focus on reliability, convenience, and innovation, Medi-O introduces features like
                QR-code–based prescription verification, automated medicine reminders, and a streamlined order
                process. Built with care and powered by technology, Medi-O is committed to becoming a trusted
                name in digital healthcare — offering a smarter way to manage your medication needs.
            </p>
            <a href="#" class="btn btn-outline-light rounded-pill px-4 py-2 medi-o-btn-outline">Read More</a>
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


    <!-- Trust Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row gy-4 text-center">

                <!-- Box 1 -->
                <div class="col-12 col-md-4">
                    <div class="trust-box p-3 text-center">
                        100% Secured Payment Methods
                    </div>
                </div>

                <!-- Box 2 -->
                <div class="col-12 col-md-4">
                    <div class="trust-box p-3 text-center">
                        365 Days Quick Support
                    </div>
                </div>

                <!-- Box 3 -->
                <div class="col-12 col-md-4">
                    <div class="trust-box p-3 text-center">
                        Trusted Pharmacist Approval
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