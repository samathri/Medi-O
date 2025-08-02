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



    <!-- Contact Us Hero Section -->
    <section class="medi-o-hero contact-hero py-5">
    </section>


    <!-- Contact Us Section -->
    <section class="contact-us-section py-5">
        <div class="container">
            <div class="text-center mb-4 ">
                <h2 class="contact-title">HAVE A QUESTION OR NEED SUPPORT?</h2>
                <p class="contact-description contact-section">We’re here to help. Reach out to us for any inquiries
                    about your orders, prescriptions, or general assistance.</p>
            </div>

            <!-- Contact Info and Inquiry Form -->
            <div class="row justify-content-center">
                <!-- Contact Info Section -->
                <div class="col-md-5 contact-info">
                    <div class="contact-info-item">
                        <i class="bi bi-geo-alt-fill "><span class="gap-c">Location</span></i>
                        <br><span>Colombo, Sri Lanka</span>
                    </div>
                    <div class="contact-info-item">
                        <i class="bi bi-envelope-fill"><span class="gap-c">Email</span></i>
                        <br><span>info@medio.com</span>
                    </div>
                    <div class="contact-info-item">
                        <i class="bi bi-telephone-fill"><span class="gap-c">Phone</span></i>
                        <br><span>+94 77 123 4567</span>
                    </div>

                    <!-- Social Media Icons -->
                    <div class="social-icons mt-4">
                        <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>

                <!-- Inquiry Form Section -->
                <div class="col-md-5 inquiry-form">
                    <h3 class="form-title">Send Your Inquiry</h3>
                    <form action="inquiry.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </section>


    <div class="page-container">
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3173.1348596502137!2d79.97726591477539!3d6.927079429369139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae258b2ef0c8f9d%3A0xf68e6191a5012337!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2sus!4v1682992125105!5m2!1sen!2sus"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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