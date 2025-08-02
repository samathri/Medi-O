<?php
include 'includes/db.php';

<<<<<<< HEAD
=======
// Handle filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$max_price = isset($_GET['max_price']) ? (float) $_GET['max_price'] : 15000;

// Build base query
$sql = "SELECT * FROM products WHERE price <= $max_price";
if (!empty($search)) {
    $searchEscaped = $conn->real_escape_string($search);
    $sql .= " AND name LIKE '%$searchEscaped%'";
}
switch ($sort) {
    case 'low_high':
        $sql .= " ORDER BY price ASC";
        break;
    case 'high_low':
        $sql .= " ORDER BY price DESC";
        break;
    case 'newest':
        $sql .= " ORDER BY created_at DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
}
$sql .= " LIMIT 40";
$result = $conn->query($sql);
?>
>>>>>>> e26e3594b6c10306cb693075c082b8dbf9dda5be

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medi-O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<!-- Header -->
<?php include("components/header.php"); ?>

<section class="medi-o-shop-page container-fluid px-3 px-md-5">
    <form method="GET" class="row">
        <!-- Sidebar -->
        <aside class="col-lg-3 mb-4">
            <h5 class="fw-bold text-primary">Categories</h5>
            <ul class="list-unstyled category-list d-none d-lg-block">
                <li><a href="#">All</a></li>
                <!-- ... other categories -->
            </ul>

<<<<<<< HEAD
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
=======
            <section class="price-slider-container mt-4">
                <h5 class="fw-bold text-primary">By Price</h5>
                <input type="range" name="max_price" id="priceSlider" min="0" max="15000"
                    value="<?php echo $max_price; ?>" step="100" class="form-range" oninput="document.getElementById('priceLabel').textContent = 'Rs. ' + this.value + '.00'">
                <div class="price-labels">
                    <span id="priceLabel">Rs. <?php echo number_format($max_price, 2); ?></span>
>>>>>>> e26e3594b6c10306cb693075c082b8dbf9dda5be
                </div>
                <button type="submit" class="btn btn-primary mt-3">Apply Filter</button>
            </section>
        </aside>

        <!-- Product Listing -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="mb-2 mb-md-0">Showing products</div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="search" placeholder="Search..." class="form-control form-control-sm"
                        value="<?php echo htmlspecialchars($search); ?>" />
                    <select name="sort" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Sort</option>
                        <option value="low_high" <?php if ($sort == 'low_high') echo 'selected'; ?>>Price: Low to High</option>
                        <option value="high_low" <?php if ($sort == 'high_low') echo 'selected'; ?>>Price: High to Low</option>
                        <option value="newest" <?php if ($sort == 'newest') echo 'selected'; ?>>Newest</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                </div>
            </div>

            <!-- Product Cards -->
            <div class="row g-3 py-3">
                <?php
                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $imagePaths = explode(',', $row['image_path']);
                        $firstImage = trim($imagePaths[0]);
                        $imageSrc = (strpos($firstImage, 'uploads/') === 0) ? $firstImage : 'uploads/' . $firstImage;
                ?>
                <div class="col-6 col-md-3 col-lg-3">
                    <div class="medi-o-product-card text-center">
                        <img src="<?php echo htmlspecialchars($imageSrc); ?>" class="bs-img img-fluid mb-3"
                            alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <h6 class="medi-o-product-title"><?php echo htmlspecialchars($row['name']); ?></h6>
                        <p class="medi-o-product-price mb-1">Rs. <?php echo number_format($row['price'], 2); ?></p>
                        <div class="text-warning">★ ★ ★ ☆ ☆</div>
                    </div>
                </div>
<<<<<<< HEAD

                <!-- Desktop view categories (hidden on small screens) -->
                <ul class="list-unstyled category-list d-none d-lg-block">
                    <li><a href="#">All</a></li>
                    <li><a href="#">Adult Care</a></li>
                    <li><a href="#">Ayurvedic Care</a></li>
                    <li><a href="#">Baby Care</a></li>
                    <li><a href="#">Dental Care</a></li>
                    <li><a href="#">Diabetes Care</a></li>
                    <li><a href="#">Dressings</a></li>
                    <li><a href="#">Doctors Equipment</a></li>
                    <li><a href="#">First Aid Items</a></li>
                    <li><a href="#">Orthopedic Aid</a></li>
                    <li><a href="#">Personal Care</a></li>
                    <li><a href="#">Supplements</a></li>
                    <li><a href="#">Veterinary</a></li>
                    <li><a href="#">Wellness Vouchers</a></li>
                </ul>
                <section class="price-slider-container">
                    <h5 class="fw-bold text-primary">By Price</h5>
                    <div class="price-slider">
                        <!-- Single Slider for price range -->
                        <input type="range" id="priceSlider" min="0" max="15000" value="7500" step="100"
                            class="form-range">

                        <!-- Price Labels Display Below Slider -->
                        <div class="price-labels">
                            <span id="priceLabel">Rs. 7500.00</span>
                        </div>

                        <!-- Filter Button below the slider -->
                        <div class="filter-btn-container">
                            <button id="filterBtn" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </div>
                </section>


            </aside>


            <!-- Products Section -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <p class="mb-2 mb-md-0">Showing 1 - 40 of 88 results</p>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Best Seller</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Newest</option>
                        </select>
                        <button class="btn btn-outline-primary btn-sm"><i class="bi bi-grid-fill"></i></button>
                        <button class="btn btn-outline-primary btn-sm"><i class="bi bi-list"></i></button>
                    </div>
                </div>

                <!-- Product Grid (use homepage card styles) -->
                <div class="row g-3 py-3">
                    <!-- Product Card 1 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Isocal-600x600.png" class="bs-img img-fluid mb-3" alt="Ortho Shield">
                            <h6 class="medi-o-product-title">ISOCAL POWDER 425g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Siddhalepa-Balm-50g 1.png" class="bs-img img-fluid mb-3"
                                alt="Siddhalepa Balm">
                            <h6 class="medi-o-product-title">SIDDHALEPA BALM 50G</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/ACNE-AID 1.png" class="bs-img img-fluid mb-3" alt="Acne-Aid Bar">
                            <h6 class="medi-o-product-title">ACNE-AID BAR 100g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 py-3">
                    <!-- Product Card 1 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Isocal-600x600.png" class="bs-img img-fluid mb-3" alt="Ortho Shield">
                            <h6 class="medi-o-product-title">ISOCAL POWDER 425g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Siddhalepa-Balm-50g 1.png" class="bs-img img-fluid mb-3"
                                alt="Siddhalepa Balm">
                            <h6 class="medi-o-product-title">SIDDHALEPA BALM 50G</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/ACNE-AID 1.png" class="bs-img img-fluid mb-3" alt="Acne-Aid Bar">
                            <h6 class="medi-o-product-title">ACNE-AID BAR 100g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 py-3">
                    <!-- Product Card 1 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Isocal-600x600.png" class="bs-img img-fluid mb-3" alt="Ortho Shield">
                            <h6 class="medi-o-product-title">ISOCAL POWDER 425g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Siddhalepa-Balm-50g 1.png" class="bs-img img-fluid mb-3"
                                alt="Siddhalepa Balm">
                            <h6 class="medi-o-product-title">SIDDHALEPA BALM 50G</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/ACNE-AID 1.png" class="bs-img img-fluid mb-3" alt="Acne-Aid Bar">
                            <h6 class="medi-o-product-title">ACNE-AID BAR 100g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="col-6  col-md-3 col-lg-3">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>
                </div>
                


                <!-- Pagination Section -->
                <div class="d-flex justify-content-center my-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">25</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>







            </div>
        </div>
    </section>












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

=======
                <?php
                    endwhile;
                else:
                    echo "<p>No products found.</p>";
                endif;
                $conn->close();
                ?>
            </div>
        </div>
    </form>
</section>
>>>>>>> e26e3594b6c10306cb693075c082b8dbf9dda5be

<!-- Footer -->
<?php include("components/footer.php"); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // JS to update price label if JS is loaded after DOM
    document.addEventListener("DOMContentLoaded", function () {
        const priceSlider = document.getElementById("priceSlider");
        if (priceSlider) {
            document.getElementById("priceLabel").textContent = 'Rs. ' + priceSlider.value + '.00';
        }
    });
</script>
</body>
</html>
