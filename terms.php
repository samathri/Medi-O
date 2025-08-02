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

    <!-- Terms and Conditions Section -->
    <section class="terms-policy-section py-5">
        <div class="container">
            <div class="text-center">
                <h1 class="terms-policy-title mb-4">Terms and Conditions</h1>
                <p class="terms-policy-description">
                    By accessing and using Medi-O, you agree to the following terms and conditions. Please read them
                    carefully before using the website or placing any orders.
                </p>
            </div>

            <!-- Policy Content -->
            <div class="terms-policy-content">
                <h3 class="terms-policy-subtitle">1. Acceptance of Terms</h3>
                <p>By registering an account, uploading prescriptions, or placing an order, you confirm that you accept
                    these terms and agree to follow them.</p>

                <h3 class="terms-policy-subtitle">2. Eligibility</h3>
                <p>You must be at least 18 years old to use Medi-O or have parental/guardian supervision. Certain
                    medications may require a valid prescription before they can be processed.</p>

                <h3 class="terms-policy-subtitle">3. Orders and Prescriptions</h3>
                <ul class="terms-policy-list">
                    <li>Orders containing prescription-required medicine must include a valid prescription.</li>
                    <li>All prescriptions are subject to pharmacist review and approval.</li>
                    <li>We reserve the right to cancel any order if the prescription is invalid or incomplete.</li>
                </ul>

                <h3 class="terms-policy-subtitle">4. Account Responsibility</h3>
                <ul class="terms-policy-list">
                    <li>Users are responsible for maintaining the confidentiality of their login credentials.</li>
                    <li>Medi-O is not liable for unauthorized access resulting from user negligence.</li>
                </ul>

                <h3 class="terms-policy-subtitle">5. Payments</h3>
                <ul class="terms-policy-list">
                    <li>All transactions must be completed using approved, secure payment methods.</li>
                    <li>Orders are confirmed only after payment is successfully processed.</li>
                </ul>

                <h3 class="terms-policy-subtitle">6. Delivery</h3>
                <ul class="terms-policy-list">
                    <li>We aim to deliver orders within the specified timeframe; however, delays may occur due to
                        unforeseen circumstances.</li>
                    <li>Medi-O is not responsible for delays caused by courier services or external factors.</li>
                </ul>

                <h3 class="terms-policy-subtitle">7. Product Availability</h3>
                <ul class="terms-policy-list">
                    <li>Product availability may vary. If an item is out of stock, you’ll be notified and offered an
                        alternative or refund.</li>
                    <li>Prices and product details may change without notice.</li>
                </ul>

                <h3 class="terms-policy-subtitle">8. Medical Disclaimer</h3>
                <ul class="terms-policy-list">
                    <li>All content provided on the site is for informational purposes only.</li>
                    <li>Always follow your doctor’s advice and consult a licensed professional before taking any
                        medication.</li>
                </ul>

                <h3 class="terms-policy-subtitle">9. Prohibited Use</h3>
                <p>Misuse of the platform (fraudulent prescriptions, false identity, abusive language) may result in
                    account suspension or legal action.</p>

                <h3 class="terms-policy-subtitle">10. Changes to Terms</h3>
                <p>Medi-O reserves the right to update these terms at any time. Updated terms will be posted on this
                    page with the updated effective date.</p>
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