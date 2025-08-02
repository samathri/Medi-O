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

    


    <!-- Privacy Policy Section -->
    <section class="privacy-policy-section py-5">
        <div class="container">
            <div class="text-center">
                <h1 class="privacy-policy-title mb-4">Privacy Policy</h1>
                <p class="privacy-policy-description">
                    At Medi-O, we are committed to protecting your privacy and handling your personal information
                    responsibly. This Privacy Policy outlines how we collect, use, store, and protect your data when you
                    use our website and services.
                </p>
            </div>

            <!-- Policy Content -->
            <div class="privacy-policy-content">

                <h3 class="privacy-policy-subtitle">1. Information We Collect</h3>
                <p>We may collect the following types of information:</p>
                <ul class="privacy-policy-list">
                    <li>Personal details (name, email, phone number, delivery address)</li>
                    <li>Account credentials (username, password – securely hashed)</li>
                    <li>Medical and prescription data (uploaded files, medication history)</li>
                    <li>Order history and product preferences</li>
                    <li>Payment details (processed securely via third-party payment gateways)</li>
                    <li>Technical information (IP address, browser type, device data)</li>
                </ul>

                <h3 class="privacy-policy-subtitle">2. How We Use Your Information</h3>
                <p>We use your information to:</p>
                <ul class="privacy-policy-list">
                    <li>Process and deliver your medicine orders</li>
                    <li>Review and approve prescriptions</li>
                    <li>Provide secure access to your account</li>
                    <li>Send order confirmations and medicine intake reminders</li>
                    <li>Improve website performance and user experience</li>
                    <li>Respond to customer support queries</li>
                </ul>

                <h3 class="privacy-policy-subtitle">3. Prescription Data Security</h3>
                <p>Uploaded prescriptions are stored securely and accessed only by authorized pharmacists for
                    verification purposes. QR-based prescription verification links are tokenized and do not expose
                    personal details to the public.</p>

                <!-- New Sections -->
                <h3 class="privacy-policy-subtitle">4. Data Sharing</h3>
                <p>We do not sell or rent your personal data. We may share your information with:</p>
                <ul class="privacy-policy-list">
                    <li>Licensed pharmacists for order verification</li>
                    <li>Delivery partners for fulfilling your order</li>
                    <li>Payment processors for secure transactions</li>
                    <li>Legal authorities, only if required by law</li>
                </ul>

                <h3 class="privacy-policy-subtitle">5. Data Protection & Security</h3>
                <p>We use SSL encryption for all data transfers</p>
                <ul class="privacy-policy-list">
                    <li>Passwords are hashed and never stored in plain text</li>
                    <li>Only authorized personnel can access sensitive information</li>
                </ul>

                <h3 class="privacy-policy-subtitle">6. Your Rights</h3>
                <p>You have the right to:</p>
                <ul class="privacy-policy-list">
                    <li>Access or update your personal information</li>
                    <li>Request deletion of your account and associated data</li>
                    <li>Withdraw consent for communication or services</li>
                    <li>To make such requests, contact us at <a href="mailto:privacy@medi-o.lk">privacy@medi-o.lk</a>
                    </li>
                </ul>

                <h3 class="privacy-policy-subtitle">7. Cookies</h3>
                <p>We use cookies to improve your browsing experience, remember login sessions, and gather anonymous
                    analytics. You can disable cookies in your browser settings.</p>

                <h3 class="privacy-policy-subtitle">8. Third-Party Links</h3>
                <p>Our website may contain links to third-party websites. We are not responsible for their privacy
                    practices or content.</p>

                <h3 class="privacy-policy-subtitle">9. Policy Updates</h3>
                <p>We may update this Privacy Policy from time to time. Changes will be posted on this page with the
                    updated effective date.</p>

                <h3 class="privacy-policy-subtitle">10. Contact Us</h3>
                <p>If you have any questions or concerns about this Privacy Policy, please contact us at: <a
                        href="mailto:privacy@medi-o.lk">privacy@medi-o.lk</a></p>

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