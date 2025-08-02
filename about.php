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
    <section class="medi-o-hero about-hero py-5">
    </section>





    <!-- About Us Section -->
    <section class="who-we-are py-5">
        <div class="container">
            <div class="row text-center">
                <h2 class="who-we-are-title mb-4">WHO WE ARE</h2>
                <p class="who-we-are-desc mb-4">
                    Medi-O was developed to transform traditional pharmacy services into a secure, digital experience —
                    allowing users to order medications, upload prescriptions, and receive verified treatments from the
                    comfort of their homes.
                    <br><br>
                    With a focus on reliability, convenience, and innovation, Medi-O introduces features like
                    QR-code-based prescription verification, automated medicine reminders, and a streamlined order
                    process. Built with care and powered by technology, Medi-O is committed to becoming a trusted name
                    in digital healthcare — offering a smarter way to manage your medication needs.
                </p>
            </div>

            <!-- Range of Products Section -->
            <div class="row my-5 align-items-center justify-content-center">
                <!-- Image on the left and Text on the right -->
                <div class="col-md-5 d-flex justify-content-center">
                    <img src="images/products.png" alt="Products Icon" class="icon-img mb-3">
                </div>
                <div class="col-md-7 text-md-start text-center">
                    <h4 class="section-title">Range of Products</h4>
                    <p class="section-desc">
                        We ensure ourselves on offering an extensive range of carefully selected products to meet the
                        everyday health and wellness needs of our customers.
                        From essential prescription medicines and over-the-counter drugs to premium skincare and
                        cosmetic items, our catalogue covers both pharmaceutical and cosmetic categories.
                    </p>
                </div>
            </div>

            <!-- Customer Care Section --><!-- Customer Care Section -->
            <div class="row my-5 align-items-center justify-content-center">
                <!-- Image on top for mobile view, left for desktop view -->
                <div class="col-12 col-md-5 d-flex justify-content-center order-1 order-md-2">
                    <img src="images/customer.png" alt="Customer Care Icon" class="icon-img mb-3">
                </div>

                <!-- Text on the bottom for mobile view, left for desktop view -->
                <div class="col-12 col-md-7 text-center text-md-start order-2 order-md-1">
                    <h4 class="section-title">Customer Care</h4>
                    <p class="section-desc">
                        Our team of professionally trained pharmacists is committed to delivering outstanding customer
                        service with care and precision. Whether you're seeking advice on your medication, assistance
                        with your prescription, or support with your order, we ensure every interaction is handled with
                        the highest level of professionalism, compassion, and trust. Your health and satisfaction are
                        our top priorities.
                    </p>
                </div>
            </div>



        </div>
    </section>












    <!-- Footer Section -->
<?php include("components/footer.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/script.js"></script>



</body>

</html>