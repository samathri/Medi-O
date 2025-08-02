<?php
session_start();
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

    

<!-- Forgot Password Form -->
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="forgot-password-form p-4 border rounded shadow-sm w-100" style="max-width: 500px;">
      <h3 class="text-center mb-3">Forgot Your Password?</h3>
      <p class="text-center text-muted mb-4">Enter your email address to receive a password reset link.</p>

      <!-- Alerts -->
      <?php if (isset($_SESSION['forgot_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $_SESSION['forgot_success']; unset($_SESSION['forgot_success']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php elseif (isset($_SESSION['forgot_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $_SESSION['forgot_error']; unset($_SESSION['forgot_error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form action="process-forgot-password.php" method="POST" novalidate>
        <div class="mb-3">
          <label for="forgotEmail" class="form-label">Email Address</label>
          <input type="email" name="email" id="forgotEmail" class="form-control" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
      </form>

      <div class="text-center mt-3">
        <p class="mb-0">Remember your password? <a href="login.php">Login here</a></p>
      </div>
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