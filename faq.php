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

    


    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <!-- Title -->
            <div class="row text-center mb-4">
                <h2 class="faq-title">FREQUENTLY ASKED QUESTIONS</h2>
                <p class="faq-subtitle">We are here to answer all your Frequently Asked Questions</p>
            </div>

            <!-- Accordion for FAQ -->
            <div class="accordion" id="faqAccordion">
                <!-- FAQ Item 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                            1. How do I place an order on Medi-O?
                        </button>
                    </h2>
                    <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Placing an order is easy! Simply register or log in, browse our medicine catalog, add items
                            to your cart, upload a prescription if required, and proceed to checkout.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading2">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                            2. Do I need a prescription to order medicine?
                        </button>
                    </h2>
                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You only need a prescription for certain types of medicines. The system will notify you if a
                            prescription is required for your chosen items.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading3">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                            3. How does prescription verification work?
                        </button>
                    </h2>
                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Once you upload your prescription, our team of certified pharmacists will review and verify
                            the document before processing your order.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading4">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                            4. How long does delivery take?
                        </button>
                    </h2>
                    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Delivery typically takes 3-5 business days, depending on your location. You will receive an
                            email with tracking information once your order has shipped.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading5">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                            5. Is it safe to order medicine online?
                        </button>
                    </h2>
                    <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, it is completely safe! We prioritize the privacy and safety of your personal and
                            payment information. We use secure payment methods and encrypted data transmission.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading6">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                            6. Can I track my order?
                        </button>
                    </h2>
                    <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, once your order is shipped, you will receive an email with a tracking link to follow
                            your order’s progress until it reaches your doorstep.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 7 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading7">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                            7. What payment methods are accepted?
                        </button>
                    </h2>
                    <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept various payment methods including credit/debit cards, mobile payments, and bank
                            transfers for your convenience.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 8 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading8">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
                            8. Can I get reminders to take my medicine?
                        </button>
                    </h2>
                    <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faqHeading8"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we offer an optional medication reminder service, which will send you notifications to
                            remind you when it’s time to take your medicine.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 9 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading9">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse9" aria-expanded="false" aria-controls="faqCollapse9">
                            9. Is my personal and medical information safe?
                        </button>
                    </h2>
                    <div id="faqCollapse9" class="accordion-collapse collapse" aria-labelledby="faqHeading9"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, your privacy is important to us. All personal and medical information is stored
                            securely and will never be shared without your consent.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 10 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading10">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faqCollapse10" aria-expanded="false" aria-controls="faqCollapse10">
                            10. Who can I contact if I need help?
                        </button>
                    </h2>
                    <div id="faqCollapse10" class="accordion-collapse collapse" aria-labelledby="faqHeading10"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can contact our customer support team by email at <a
                                href="mailto:info@medio.com">info@medio.com</a> or by calling our helpline at +94 77 123
                            4567.
                        </div>
                    </div>
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