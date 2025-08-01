<?php
session_start();
include 'includes/db.php'; // Your DB connection file

$error = '';
$success = '';

// If user is already logged in, redirect based on role
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            header("Location: admin.php");
            break;
        case 'pharmacist':
            header("Location: pharmacist.php");
            break;
        case 'customer':
        default:
            header("Location: profile.php");
            break;
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Prepare and execute query to find user by email
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password hash
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: admin.php");
                        break;
                    case 'pharmacist':
                        header("Location: pharmacist.php");
                        break;
                    case 'customer':
                    default:
                        header("Location: profile.php");
                        break;
                }
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }
        $stmt->close();
    }
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
            <a class="medi-o-navbar-brand navbar-brand" href="home.html">
                <img src="images/medi-o-logo.svg" alt="Medi-O Logo" class="medi-o-logo logo-h" />
            </a>
            <button class="medi-o-navbar-toggler navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#medi-o-navbarMenu" aria-controls="medi-o-navbarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="medi-o-navbar-toggler-icon navbar-toggler-icon"></span>
            </button>

            <div class="medi-o-navbar-collapse collapse navbar-collapse" id="medi-o-navbarMenu">
                <div class="w-100 d-lg-flex flex-lg-column align-items-lg-end medi-o-navbar-collapse-inner">
                    <ul class="medi-o-navbar-nav navbar-nav flex-lg-row mb-2 gap-3 mb-lg-0">
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="home.html">Home</a></li>
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="shop.html">Shop</a></li>
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="about.html">About Us</a></li>
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="contact.html">Contact Us</a></li>
                    </ul>
                    <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
                        <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                        <a href="cart.html" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>
                        <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                    </div>
                    <div class="medi-o-nav-icons-mobile nav-icons d-lg-none mt-3">
                        <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                        <a href="cart.html" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>
                        <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="container d-flex justify-content-center align-items-center signin-view vh-100">
    <div class="login-form p-4 border rounded">
        <h3 class="text-center mb-4">Login to Your Account</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" id="loginForm">
            <div class="mb-3">
                <label for="loginEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Enter your email" required />
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter your password" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="forgot-password.html">Forgot Password?</a>
        </div>
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</div>

<footer class="medi-o-footer text-white py-5" style="background-color: #0467BB;">
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
                <h5 class="fw-semibold mb-4 ">Contact Us</h5>
                <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i class="bi bi-geo-alt-fill me-2"></i>Colombo, Sri Lanka</a></p>
                <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i class="bi bi-envelope-fill me-2"></i>info@medio.com</a></p>
                <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i class="bi bi-telephone-fill me-2"></i>+94 77 123 4567</i></a></p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
