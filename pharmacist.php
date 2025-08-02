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
        <a class="medi-o-navbar-brand navbar-brand" href="home.php">
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
              <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="home.php">Home</a></li>
              <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="shop.php">Shop</a></li>
              <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="about.php">About
                  Us</a></li>
              <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="contact.php">Contact
                  Us</a></li>
            </ul>

            <!-- Desktop Icons -->
            <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
              <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
              <a href="cart.php" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>

              <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                <div class="dropdown">
                  <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none" href="#" id="userDropdown"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                    id="userDropdownMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

            <form class="medi-o-search-form-popup position-relative" role="search" aria-label="Popup site search">
              <input type="search" id="medi-o-search-input-popup" class="form-control medi-o-search-input-popup"
                placeholder="Search Here....." aria-labelledby="medi-o-search-label-popup" autocomplete="off" />
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


  <div class="pharmacist-dashboard">

    <h1 class="pharmacist-dashboard__title">Pharmacist Dashboard</h1>

    <!-- Assigned Prescriptions Table -->
    <section aria-label="Assigned Prescriptions">
      <h2 class="pharmacist-dashboard__section-title">Assigned Prescriptions</h2>
      <div class="pharmacist-dashboard__table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Patient Name</th>
              <th>Upload Date</th>
              <th>Prescription File</th>
              <th>Status</th>
              <th>QR Code</th>
              <th class="text-center">Update Status</th>
            </tr>
          </thead>
          <tbody id="prescriptionsTableBody">
            <!-- Example row -->
            <tr>
              <td>John Doe</td>
              <td>2025-07-28</td>
              <td>
                <a href="prescription_01.pdf" target="_blank" rel="noopener"
                  aria-label="View prescription file for John Doe" class="pharmacist-dashboard__link">
                  prescription_01.pdf <i class="bi bi-box-arrow-up-right"></i>
                </a>
              </td>
              <td><span class="pharmacist-dashboard__badge pharmacist-dashboard__badge--assigned">Assigned</span></td>
              <td>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=prescription_01"
                  alt="QR Code for prescription 01" class="pharmacist-dashboard__qr-code"
                  title="Click to enlarge QR Code" onclick="showQRModal('prescription_01')" />
              </td>
              <td class="text-center">
                <div class="btn-group" role="group" aria-label="Update prescription status buttons">
                  <button class="btn btn-warning pharmacist-dashboard__btn-sm" onclick="updateStatus(this, 'Packing')"
                    title="Start Packing">Start Packing</button>
                  <button class="btn btn-success pharmacist-dashboard__btn-sm" onclick="updateStatus(this, 'Ready')"
                    title="Mark as Ready">Complete & Ready</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>Mary Smith</td>
              <td>2025-07-20</td>
              <td>
                <a href="prescription_02.pdf" target="_blank" rel="noopener"
                  aria-label="View prescription file for Mary Smith" class="pharmacist-dashboard__link">
                  prescription_02.pdf <i class="bi bi-box-arrow-up-right"></i>
                </a>
              </td>
              <td><span class="pharmacist-dashboard__badge pharmacist-dashboard__badge--pending"
                  style="color:#212529;">Packing</span></td>
              <td>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=prescription_02"
                  alt="QR Code for prescription 02" class="pharmacist-dashboard__qr-code"
                  title="Click to enlarge QR Code" onclick="showQRModal('prescription_02')" />
              </td>
              <td class="text-center">
                <div class="btn-group" role="group" aria-label="Update prescription status buttons">
                  <button class="btn btn-warning pharmacist-dashboard__btn-sm" disabled title="Already packing">Start
                    Packing</button>
                  <button class="btn btn-success pharmacist-dashboard__btn-sm" onclick="updateStatus(this, 'Ready')"
                    title="Mark as Ready">Complete & Ready</button>
                </div>
              </td>
            </tr>
            <!-- More rows dynamically added here -->
          </tbody>
        </table>
      </div>
    </section>

    <!-- Activity History -->
    <section aria-label="Activity History" class="mt-5">
      <h2 class="pharmacist-dashboard__section-title">Activity History</h2>
      <div class="pharmacist-dashboard__table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Date/Time</th>
              <th>Prescription ID</th>
              <th>Previous Status</th>
              <th>Updated Status</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody id="activityHistoryBody">
            <!-- Example logs -->
            <tr>
              <td>2025-07-28 15:30</td>
              <td>P001</td>
              <td>Assigned</td>
              <td>Packing</td>
              <td>Started packing</td>
            </tr>
            <tr>
              <td>2025-07-29 10:15</td>
              <td>P002</td>
              <td>Packing</td>
              <td>Ready</td>
              <td>Marked as ready for pickup</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qrModalLabel">Prescription QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img id="qrModalImg" src="" alt="QR Code Enlarged" style="max-width: 100%; height: auto;" />
          </div>
        </div>
      </div>
    </div>

  </div>





  <!-- Footer Section -->
  <footer class="medi-o-footer text-white py-5" style="background-color: #0467BB;">
    <div class="container medi-o-container">
      <div class="row gy-4 text-center text-md-start">
        <!-- Logo & Description -->
        <div class="col-md-6">
          <a href="home.php"><img src="images/medi-o-logo-white.svg" alt="Medi-O Logo logo-f" height="60"
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



</body>

</html>