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

    

    <!-- Order Success Section -->
    <section class="order-success py-5">
        <div class="container">
            <div class="text-center">
                <h2 class="mb-4">Thank You for Your Order!</h2>
                <p class="lead mb-4">Your order has been successfully placed. We will notify you once it is shipped.</p>

                <!-- Order Number -->
                <div class="alert alert-success mb-4" role="alert">
                    <strong>Order Number: </strong> #ORD1234567
                </div>

                <!-- Order Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-semibold">Order Summary</h5>
                        <ul class="list-unstyled">
                            <li>Subtotal: Rs. 5,110.00</li>
                            <li>Shipping: Rs. 150.00</li>
                            <li><strong>Total: Rs. 5,260.00</strong></li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h5 class="fw-semibold">Shipping Information</h5>
                        <ul class="list-unstyled">
                            <li><strong>Name:</strong> John Doe</li>
                            <li><strong>Address:</strong> 123, Elm Street, Colombo</li>
                            <li><strong>Phone:</strong> +94 77 123 4567</li>
                            <li><strong>Email:</strong> john.doe@example.com</li>
                        </ul>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex justify-content-center gap-4">
                    <a href="index.php" class="btn btn-primary">Return to Homepage</a>
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