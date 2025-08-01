<?php
ob_start(); // Start output buffering
include 'includes/db.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name             = trim($_POST["fullname"]);
    $email            = trim($_POST["email"]);
    $password         = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $phone            = trim($_POST["phone"] ?? '');
    $address          = trim($_POST["address"] ?? '');

    // Basic Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        die("Please fill in all required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $address);

    // Execute and redirect
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: login.php?signup=success"); // Redirect to login
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medi-O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

    <!-- Header -->
    <header class="medi-o-header-container">
        <nav class="medi-o-navbar navbar navbar-expand-lg">
            <div class="container-fluid medi-o-container-fluid">

                <!-- Logo (left) -->
                <a class="medi-o-navbar-brand navbar-brand" href="home.html">
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
                                    href="home.html">Home</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="shop.html">Shop</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="about.html">About
                                    Us</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="contact.html">Contact
                                    Us</a></li>
                        </ul>

                        <!-- Desktop Icons -->
                        <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
                            <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                            <a href="cart.html" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>
                            <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                        </div>

                        <!-- Mobile Icons -->
                        <div class="medi-o-nav-icons-mobile nav-icons d-lg-none mt-3">
                            <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                            <a href="cart.html" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>
                            <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                        </div>

                    </div>
                </div>

  <!-- Search Popup Overlay -->
<div class="medi-o-search-overlay d-none" id="searchOverlay">
    <div class="medi-o-search-popup" role="dialog" aria-label="Search popup">
        <!-- Close Button (aligned to the top-right corner) -->
        <button type="button" class="btn medi-o-search-close" aria-label="Close search popup" onclick="toggleSearchPopup(false)">
            &times;
        </button>

        <form class="medi-o-search-form-popup position-relative" role="search" aria-label="Popup site search">
            <input type="search" id="medi-o-search-input-popup" class="form-control medi-o-search-input-popup" placeholder="Search Here....." aria-labelledby="medi-o-search-label-popup" autocomplete="off" />
            <label id="medi-o-search-label-popup" for="medi-o-search-input-popup" class="visually-hidden">Search</label>

            <button type="submit" class="btn medi-o-search-btn-popup" aria-label="Submit popup search">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>



            </div>
        </nav>
    </header>
<!-- Signup Form -->
<div class="signup-form p-4 border rounded shadow-sm" style="max-width: 500px; width: 100%; background: #fff; margin: auto;">
    <h3 class="text-center mb-4" style="font-weight: 600;">Create Your Account</h3>
    <form action="signup.php" method="POST" id="signupForm">
        <div class="mb-3">
            <label for="signupFullName" class="form-label">Full Name</label>
            <input type="text" class="form-control rounded-3 py-2" id="signupFullName" name="fullname" placeholder="Enter your full name" required />
        </div>
        <div class="mb-3">
            <label for="signupEmail" class="form-label">Email</label>
            <input type="email" class="form-control rounded-3 py-2" id="signupEmail" name="email" placeholder="Enter your email" required />
        </div>
        <div class="mb-3">
            <label for="signupPhone" class="form-label">Phone</label>
            <input type="text" class="form-control rounded-3 py-2" id="signupPhone" name="phone" placeholder="Enter your phone number" />
        </div>
        <div class="mb-3">
            <label for="signupAddress" class="form-label">Address</label>
            <input type="text" class="form-control rounded-3 py-2" id="signupAddress" name="address" placeholder="Enter your address" />
        </div>
        <div class="mb-3">
            <label for="signupPassword" class="form-label">Password</label>
            <input type="password" class="form-control rounded-3 py-2" id="signupPassword" name="password" placeholder="Create a password" required />
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control rounded-3 py-2" id="confirmPassword" name="confirm_password" placeholder="Confirm your password" required />
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill" style="background-color: #0066cc; font-weight: 600;">Sign Up</button>
    </form>

    <div class="text-center mt-3">
        <p class="mb-0">Already have an account? <a href="login.html" class="text-primary" style="font-weight: 500;">Login</a></p>
    </div>
</div>

<!-- Footer -->
<footer class="medi-o-footer text-white signup-view py-5" style="background-color: #0467BB;">
    <div class="container medi-o-container">
        <div class="row gy-4 text-center text-md-start">
            <div class="col-md-6">
                <a href="home.html"><img src="images/medi-o-logo-white.svg" alt="Medi-O Logo logo-f" height="60" class="medi-o-footer-logo mb-3 logo-f" /></a>
                <p class="medi-o-footer-description mb-0">
                    Medi-O is a licensed and registered pharmacy compliant with NMRA (National Medicine Regulatory Authority).<br />
                    Registration No: <strong>(NMRA/R/5255/2022)</strong>.
                </p>
            </div>
            <div class="col-md-3">
                <h5 class="fw-semibold mb-4">Quick Links</h5>
                <ul class="list-unstyled medi-o-footer-links">
                    <li><a href="faq.html" class="text-white text-decoration-none d-block mb-2">FAQs</a></li>
                    <li><a href="about.html" class="text-white text-decoration-none d-block mb-2">About Us</a></li>
                    <li><a href="contact.html" class="text-white text-decoration-none d-block mb-2">Contact Us</a></li>
                    <li><a href="terms.html" class="text-white text-decoration-none d-block mb-2">Terms & Conditions</a></li>
                    <li><a href="privacy.html" class="text-white text-decoration-none d-block">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5 class="fw-semibold mb-4">Contact Us</h5>
                <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i class="bi bi-geo-alt-fill me-2"></i>Colombo, Sri Lanka</a></p>
                <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i class="bi bi-envelope-fill me-2"></i>info@medio.com</a></p>
                <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i class="bi bi-telephone-fill me-2"></i>+94 77 123 4567</a></p>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>

<?php ob_end_flush(); ?>
