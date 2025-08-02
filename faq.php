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
                <a class="medi-o-navbar-brand navbar-brand" href="index.php">
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
                                    href="index.php">Home</a></li>
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


    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <!-- Title -->
            <div class="row text-center mb-4">
                <h2 class="faq-title">FREQUENTLY ASKED QUESTIONS</h2>
                <p class="faq-subtitle">We are here to answer all your Frequently Asked Questions</p>
            </div>

            <!-- Accordion for FAQ -->
            <div class="accordion" id="faqAccordion">
                <!-- FAQ Item 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                            1. How do I place an order on Medi-O?
                        </button>
                    </h2>
                    <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Placing an order is easy! Simply register or log in, browse our medicine catalog, add items
                            to your cart, upload a prescription if required, and proceed to checkout.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading2">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                            2. Do I need a prescription to order medicine?
                        </button>
                    </h2>
                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You only need a prescription for certain types of medicines. The system will notify you if a
                            prescription is required for your chosen items.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading3">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                            3. How does prescription verification work?
                        </button>
                    </h2>
                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Once you upload your prescription, our team of certified pharmacists will review and verify
                            the document before processing your order.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading4">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                            4. How long does delivery take?
                        </button>
                    </h2>
                    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Delivery typically takes 3-5 business days, depending on your location. You will receive an
                            email with tracking information once your order has shipped.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading5">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                            5. Is it safe to order medicine online?
                        </button>
                    </h2>
                    <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, it is completely safe! We prioritize the privacy and safety of your personal and
                            payment information. We use secure payment methods and encrypted data transmission.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading6">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                            6. Can I track my order?
                        </button>
                    </h2>
                    <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, once your order is shipped, you will receive an email with a tracking link to follow
                            your order’s progress until it reaches your doorstep.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 7 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading7">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                            7. What payment methods are accepted?
                        </button>
                    </h2>
                    <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept various payment methods including credit/debit cards, mobile payments, and bank
                            transfers for your convenience.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 8 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading8">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
                            8. Can I get reminders to take my medicine?
                        </button>
                    </h2>
                    <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faqHeading8"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we offer an optional medication reminder service, which will send you notifications to
                            remind you when it’s time to take your medicine.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 9 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading9">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse9" aria-expanded="false" aria-controls="faqCollapse9">
                            9. Is my personal and medical information safe?
                        </button>
                    </h2>
                    <div id="faqCollapse9" class="accordion-collapse collapse" aria-labelledby="faqHeading9"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, your privacy is important to us. All personal and medical information is stored
                            securely and will never be shared without your consent.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 10 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading10">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse10" aria-expanded="false" aria-controls="faqCollapse10">
                            10. Who can I contact if I need help?
                        </button>
                    </h2>
                    <div id="faqCollapse10" class="accordion-collapse collapse" aria-labelledby="faqHeading10"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can contact our customer support team by email at <a
                                href="mailto:info@medio.com">info@medio.com</a> or by calling our helpline at +94 77 123
                            4567.
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
                    <a href="index.php"><img src="images/medi-o-logo-white.svg" alt="Medi-O Logo logo-f" height="60"
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