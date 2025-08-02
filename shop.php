<?php
include 'includes/db.php';

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

            <section class="price-slider-container mt-4">
                <h5 class="fw-bold text-primary">By Price</h5>
                <input type="range" name="max_price" id="priceSlider" min="0" max="15000"
                    value="<?php echo $max_price; ?>" step="100" class="form-range" oninput="document.getElementById('priceLabel').textContent = 'Rs. ' + this.value + '.00'">
                <div class="price-labels">
                    <span id="priceLabel">Rs. <?php echo number_format($max_price, 2); ?></span>
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
