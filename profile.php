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
    <header class="medi-o-header-container">
        <nav class="medi-o-navbar navbar navbar-expand-lg">
            <div class="container-fluid medi-o-container-fluid">

                <!-- Logo (left) -->
                <a class="medi-o-navbar-brand navbar-brand" href="index.php">
                    <img src="images/medi-o-logo.svg" alt="Medi-O Logo" class="medi-o-logo logo-h" />
                </a>

                <!-- Hamburger button (right) -->
                <button class="medi-o-navbar-toggler navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#medi-o-navbarMenu" aria-controls="medi-o-navbarMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="medi-o-navbar-toggler-icon navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="medi-o-navbar-collapse collapse navbar-collapse" id="medi-o-navbarMenu">
                    <div class="w-100 d-lg-flex flex-lg-column align-items-lg-end medi-o-navbar-collapse-inner">

                        <!-- Nav Links -->
                        <ul class="medi-o-navbar-nav navbar-nav flex-lg-row mb-2 gap-3 mb-lg-0">
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="index.php">Home</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="shop.php">Shop</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="about.php">About
                                    Us</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="contact.php">Contact
                                    Us</a></li>
                        </ul>

                        <!-- Desktop Icons -->
                        <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
                            <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                            <a href="cart.php" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>

                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                                <div class="dropdown">
                                    <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none" href="#"
                                        id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i
                                            class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>

                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                            <?php endif; ?>
                        </div>


                        <div class="medi-o-nav-icons-mobile nav-icons d-lg-none mt-3">
                            <a href="#" class="medi-o-nav-icon-link nav-m"><i class="bi bi-search"></i></a>
                            <a href="cart.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-cart"></i></a>

                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                                <div class="dropdown">
                                    <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none nav-m" href="#"
                                        id="userDropdownMobile" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i
                                            class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>

                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownMobile">
                                        <li><a class="dropdown-item nav-m" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item text-danger nav-m" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-person"></i></a>
                            <?php endif; ?>
                        </div>


                    </div>
                </div>

                <!-- Search Popup Overlay -->
                <div class="medi-o-search-overlay d-none" id="searchOverlay">
                    <div class="medi-o-search-popup" role="dialog" aria-label="Search popup">
                        <!-- Close Button (aligned to the top-right corner) -->
                        <button type="button" class="btn medi-o-search-close" aria-label="Close search popup"
                            onclick="toggleSearchPopup(false)">
                            &times;
                        </button>

                        <form class="medi-o-search-form-popup position-relative" role="search"
                            aria-label="Popup site search">
                            <input type="search" id="medi-o-search-input-popup"
                                class="form-control medi-o-search-input-popup" placeholder="Search Here....."
                                aria-labelledby="medi-o-search-label-popup" autocomplete="off" />
                            <label id="medi-o-search-label-popup" for="medi-o-search-input-popup"
                                class="visually-hidden">Search</label>

                            <button type="submit" class="btn medi-o-search-btn-popup" aria-label="Submit popup search">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>



            </div>
        </nav>
    </header>



    <main class="container my-5 c-profile">

        <!-- Prescription Status Dashboard -->
        <section aria-labelledby="dashboard-section-title" class="mb-5">
            <h2 id="dashboard-section-title" class="section-title">Prescription Dashboard</h2>

            <div class="progress-steps-container">
                <div class="progress-step completed">
                    <div class="step-dot">1</div>
                    <div class="step-label">Pending</div>
                </div>
                <div class="progress-step completed">
                    <div class="step-dot">2</div>
                    <div class="step-label">Order Processing</div>
                </div>
                <div class="progress-step">
                    <div class="step-dot">3</div>
                    <div class="step-label">Ready to Collect</div>
                </div>
            </div>
        </section>

        <!-- My Prescriptions Section -->
        <section aria-labelledby="prescriptions-section-title" class="form-section">
            <h2 id="prescriptions-section-title" class="section-title">My Prescriptions</h2>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">File Name</th>
                            <th scope="col">Upload Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Pharmacist</th>
                            <th scope="col">Collected Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="prescriptionList">
                        <tr>
                            <td>prescription1.pdf</td>
                            <td>2023-07-10</td>
                            <td><span class="text-warning">Pending</span></td>
                            <td>John Smith</td>
                            <td>-</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Download</a>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>prescription2.pdf</td>
                            <td>2023-06-25</td>
                            <td><span class="text-info">Order Processing</span></td>
                            <td>Mary Jones</td>
                            <td>2023-07-05</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Download</a>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>prescription3.pdf</td>
                            <td>2023-06-20</td>
                            <td><span class="text-primary">Collected</span></td>
                            <td>David Lee</td>
                            <td>2023-06-30</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Download</a>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>prescription4.pdf</td>
                            <td>2023-07-01</td>
                            <td><span class="text-success">Ready to Collect</span></td>
                            <td>Emily Johnson</td>
                            <td>-</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Download</a>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>


        <!-- Account Section -->
        <section aria-labelledby="account-section-title" class="form-section">
            <h2 id="account-section-title" class="section-title">Account Details</h2>
            <form id="profileForm" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="John Doe" required />
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="john@example.com" required />
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" placeholder="+94 77 123 4567" required />
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="123 Street, City, Country"
                            required />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
            </form>

            <!-- Change Password -->
            <hr class="my-4" />
            <h3 class="section-title">Change Password</h3>
            <form id="passwordForm" novalidate>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="oldPassword" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="oldPassword" required />
                    </div>
                    <div class="col-md-4">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required />
                    </div>
                    <div class="col-md-4">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary mt-3">Update Password</button>
            </form>
        </section>

    </main>







    <!-- Footer Section -->
    <footer class="medi-o-footer text-white py-5" style="background-color: #0467BB;">
        <div class="container medi-o-container">
            <div class="row gy-4 text-center text-md-start">
                <!-- Logo & Description -->
                <div class="col-md-6">
                    <a href="index.php"><img src="images/medi-o-logo-white.svg" alt="Medi-O Logo logo-f" height="60"
                            class="medi-o-footer-logo mb-3 logo-f" /></a>
                    <p class="medi-o-footer-description mb-0">
                        Medi-O is a licensed and registered pharmacy compliant with NMRA (National Medicine Regulatory
                        Authority).<br />
                        Registration No: <strong>(NMRA/R/5255/2022)</strong>.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-3">
                    <h5 class="fw-semibold mb-4">Quick Links</h5>
                    <ul class="list-unstyled medi-o-footer-links">
                        <li><a href="faq.php" class="text-white text-decoration-none d-block mb-2">FAQs</a></li>
                        <li><a href="about.php" class="text-white text-decoration-none d-block mb-2">About Us</a></li>
                        <li><a href="contact.php" class="text-white text-decoration-none d-block mb-2">Contact Us</a>
                        </li>
                        <li><a href="terms.php" class="text-white text-decoration-none d-block mb-2">Terms &
                                Conditions</a></li>
                        <li><a href="privacy.php" class="text-white text-decoration-none d-block">Privacy Policy</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-3">
                    <h5 class="fw-semibold mb-4 ">Contact Us</h5>
                    <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i
                                class="bi bi-geo-alt-fill me-2"></i>Colombo, Sri Lanka</a></p>
                    <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i
                                class="bi bi-envelope-fill me-2"></i>info@medio.com</a></p>
                    <p class="mb-3"><a href="#" class="footer-contact text-white fs-5"><i
                                class="bi bi-telephone-fill me-2"></i>+94 77 123 4567</i></a></p>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const steps = document.querySelectorAll('.progress-step');

            // Suppose currentStatus can be 'pending', 'order-processing', or 'ready-to-collect'
            // Map each status to index 0,1,2
            const currentStatus = 'order-processing'; // Example

            const statusMap = {
                'pending': 0,
                'order-processing': 1,
                'ready-to-collect': 2
            };

            const currentIndex = statusMap[currentStatus];

            steps.forEach((step, index) => {
                if (index <= currentIndex) {
                    step.classList.add('completed');
                } else {
                    step.classList.remove('completed');
                }
            });
        });

    </script>


</body>

</html>