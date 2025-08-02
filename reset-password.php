<?php
session_start();
require 'includes/db.php'; // Your DB connection file

// Get token from URL param
$token = $_GET['token'] ?? '';

// Validate token presence
if (!$token) {
    $_SESSION['reset_error'] = 'Invalid or missing token.';
    header('Location: login.php');
    exit;
}

// Lookup user with token
$stmt = $conn->prepare("SELECT id, reset_expires FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// Check if the token exists in the DB
if ($result->num_rows !== 1) {
    $_SESSION['reset_error'] = 'Token not found.';
    header('Location: login.php');
    exit;
}

// Fetch user data
$user = $result->fetch_assoc();

// Check if the token has expired
if (strtotime($user['reset_expires']) < time()) {
    $_SESSION['reset_error'] = 'Token has expired.';
    header('Location: login.php');
    exit;
}

// Save user data in session to identify the user during password reset
$_SESSION['reset_user_id'] = $user['id'];
$_SESSION['reset_token'] = $token;
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
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>

<!-- Header -->
<?php include("components/header.php"); ?>

    

 
    <!-- Password Reset Form -->
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="p-4 border rounded shadow-sm" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-3">Reset Your Password</h3>

            <?php if (isset($_SESSION['reset_error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['reset_error']; unset($_SESSION['reset_error']); ?></div>
            <?php endif; ?>

            <form action="process-reset-password.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="newPass" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="newPass" class="form-control" required minlength="6">
                </div>
                <div class="mb-3">
                    <label for="confirmPass" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirmPass" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Password</button>
            </form>
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