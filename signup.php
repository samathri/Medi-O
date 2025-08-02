<?php
ob_start(); // Start output buffering
include 'includes/db.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $phone = trim($_POST["phone"] ?? '');
    $address = trim($_POST["address"] ?? '');

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
<?php include("components/header.php"); ?>

    

    <!-- Signup Form -->
    <div class="signup-form p-4 border rounded shadow-sm signup-view"
        style="max-width: 500px; width: 100%; background: #fff; margin: auto;">
        <h3 class="text-center mb-4" style="font-weight: 600;">Create Your Account</h3>
        <form action="signup.php" method="POST" id="signupForm">
            <div class="mb-3">
                <label for="signupFullName" class="form-label">Full Name</label>
                <input type="text" class="form-control rounded-3 py-2" id="signupFullName" name="fullname"
                    placeholder="Enter your full name" required />
            </div>
            <div class="mb-3">
                <label for="signupEmail" class="form-label">Email</label>
                <input type="email" class="form-control rounded-3 py-2" id="signupEmail" name="email"
                    placeholder="Enter your email" required />
            </div>
            <div class="mb-3">
                <label for="signupPhone" class="form-label">Phone</label>
                <input type="text" class="form-control rounded-3 py-2" id="signupPhone" name="phone"
                    placeholder="Enter your phone number" />
            </div>
            <div class="mb-3">
                <label for="signupAddress" class="form-label">Address</label>
                <input type="text" class="form-control rounded-3 py-2" id="signupAddress" name="address"
                    placeholder="Enter your address" />
            </div>
            <div class="mb-3">
                <label for="signupPassword" class="form-label">Password</label>
                <input type="password" class="form-control rounded-3 py-2" id="signupPassword" name="password"
                    placeholder="Create a password" required />
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control rounded-3 py-2" id="confirmPassword" name="confirm_password"
                    placeholder="Confirm your password" required />
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill"
                style="background-color: #0066cc; font-weight: 600;">Sign Up</button>
        </form>

        <div class="text-center mt-3">
            <p class="mb-0">Already have an account? <a href="login.php" class="text-primary"
                    style="font-weight: 500;">Login</a></p>
        </div>
    </div>



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

<?php ob_end_flush(); ?>