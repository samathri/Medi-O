<?php
include 'includes/db.php';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<!-- Header -->
<?php include("components/header.php"); ?>

<section class="medi-o-shop-page container-fluid px-3 px-md-5">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-lg-3 mb-4">
            <h5 class="fw-bold text-primary">Categories</h5>
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
            <section class="price-slider-container mt-4">
                <h5 class="fw-bold text-primary">By Price</h5>
                <div class="price-slider">
                    <input type="range" id="priceSlider" min="0" max="15000" value="7500" step="100" class="form-range">
                    <div class="price-labels">
                        <span id="priceLabel">Rs. 7500.00</span>
                    </div>
                    <div class="filter-btn-container">
                        <button id="filterBtn" class="btn btn-primary">Apply Filter</button>
                    </div>
                </div>
            </section>
        </aside>

        <!-- Product Listing -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <p class="mb-2 mb-md-0">Showing products</p>
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

            <!-- Product Cards -->
            <div class="row g-3 py-3">
                <?php
                $sql = "SELECT * FROM products LIMIT 40";
                $result = $conn->query($sql);
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                <div class="col-6 col-md-3 col-lg-3">
                    <div class="medi-o-product-card text-center">
                    <?php
$imagePaths = explode(',', $row['image_path']);
$firstImage = trim($imagePaths[0]);

// Avoid adding 'uploads/' twice
$imageSrc = (strpos($firstImage, 'uploads/') === 0) ? $firstImage : 'uploads/' . $firstImage;
?>
<img src="<?php echo htmlspecialchars($imageSrc); ?>" class="bs-img img-fluid mb-3" alt="<?php echo htmlspecialchars($row['name']); ?>">

                        <h6 class="medi-o-product-title"><?php echo htmlspecialchars($row['name']); ?></h6>
                        <p class="medi-o-product-price mb-1">Rs. <?php echo number_format($row['price'], 2); ?></p>
                        <div class="text-warning">★ ★ ★ ☆ ☆</div>
                    </div>
                </div>
                <?php
                    endwhile;
                else:
                    echo "<p>No products found.</p>";
                endif;
                $conn->close();
                ?>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center my-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">25</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include("components/footer.php"); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
