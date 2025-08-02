<?php
include 'includes/db.php';

// Handle filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$max_price = isset($_GET['max_price']) ? (float) $_GET['max_price'] : 15000;
$categoryFilter = isset($_GET['category']) ? trim($_GET['category']) : '';
$viewMode = isset($_GET['view']) ? $_GET['view'] : 'grid';

// Pagination setup
$itemsPerPage = 16;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Fetch all unique categories
$categories = [];
$catResult = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
if ($catResult) {
    while ($catRow = $catResult->fetch_assoc()) {
        if (!empty($catRow['category'])) {
            $categories[] = $catRow['category'];
        }
    }
}

// Escape
$categoryEscaped = $conn->real_escape_string($categoryFilter);
$searchEscaped = $conn->real_escape_string($search);

// Total product count for current filters
$countSql = "SELECT COUNT(*) as total FROM products WHERE price <= $max_price";
if (!empty($categoryFilter)) $countSql .= " AND category = '$categoryEscaped'";
if (!empty($search)) $countSql .= " AND name LIKE '%$searchEscaped%'";
$countResult = $conn->query($countSql);
$totalItems = ($countResult) ? (int) $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalItems / $itemsPerPage);

// Product query
$sql = "SELECT * FROM products WHERE price <= $max_price";
if (!empty($categoryFilter)) $sql .= " AND category = '$categoryEscaped'";
if (!empty($search)) $sql .= " AND name LIKE '%$searchEscaped%'";
switch ($sort) {
    case 'low_high': $sql .= " ORDER BY price ASC"; break;
    case 'high_low': $sql .= " ORDER BY price DESC"; break;
    case 'newest': $sql .= " ORDER BY created_at DESC"; break;
    default: $sql .= " ORDER BY id DESC";
}
$sql .= " LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

// Helper
function buildUrl($params) {
    return 'shop.php?' . http_build_query($params);
}
$currentFilters = [
    'search' => $search,
    'sort' => $sort,
    'max_price' => $max_price,
    'category' => $categoryFilter,
    'view' => $viewMode
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Medi-O</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .list-view-card {
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: box-shadow 0.2s;
        }
        .list-view-card:hover {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .list-view-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .list-view-card .name {
            font-weight: 500;
            font-size: 1.1rem;
        }
        .list-view-card .desc {
            color: #555;
            font-size: 0.9rem;
        }
        .list-view-card .price {
            color: #007bff;
            font-weight: 600;
        }
        .product-rating {
            color: #f8b400;
        }
        
/* Price Slider Container */
.price-slider-container {
    max-width: 1000px !important;
    margin: 0 auto;
    padding: 20px;
    text-align: left;
}

/* Slider styling */
.form-range {
    width: 100%;
    background: transparent;
    -webkit-appearance: none;
    appearance: none;
    position: relative;
    z-index: 2;
}

/* Slider track (background) */
.form-range::-webkit-slider-runnable-track {
    width: 100%;
    height: 15px;
    background: #ffffff00 !important;
    border-radius: 5px;
}

/* Slider thumb (dot) */
.form-range::-webkit-slider-thumb {
    -webkit-appearance: none !important;
    appearance: none !important;
    width: 25px !important;
    height: 25px !important;
    border-radius: 50% !important;
    background: #0467BB !important;
    cursor: pointer !important;
    box-shadow: 0 0 2px rgba(0, 0, 0, 0.4) !important;
}

/* Price Labels (displayed below slider) */
.price-labels {
    display: block;
    margin-top: 10px;
    font-size: 1.2rem;
    color: #333;
}

.price-labels span {
    color: #000000 !important;
}

/* Filter Button Styling */
.filter-btn-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

#filterBtn {
    padding: 10px 20px;
    background-color: #0467BB !important;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

#filterBtn:hover {
    background-color: #03548a; /* Darker shade of blue */
}

/* Optional: Smooth transition for range thumb */
.form-range {
    transition: background-color 0.2s ease-in-out;
}

    </style>
</head>
<body>

<?php include("components/header.php"); ?>

<section class="medi-o-shop-page container-fluid px-3 px-md-5">
    <form method="GET" class="row">
        <aside class="col-lg-3 mb-4">
            <h5 class="fw-bold text-primary">Categories</h5>
            <ul class="list-unstyled category-list d-none d-lg-block">
                <li>
                    <a href="<?php echo buildUrl(array_merge($currentFilters, ['category' => '', 'page' => 1])); ?>"
                       <?php if ($categoryFilter === '') echo 'class="fw-bold text-primary"'; ?>>All</a>
                </li>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?php echo buildUrl(array_merge($currentFilters, ['category' => $cat, 'page' => 1])); ?>"
                           <?php if ($cat === $categoryFilter) echo 'class="fw-bold text-primary"'; ?>>
                           <?php echo htmlspecialchars($cat); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <section class="price-slider-container mt-4">
                <h5 class="fw-bold text-primary">By Price</h5>
                <input type="range" name="max_price" id="priceSlider" min="0" max="15000"
                    value="<?php echo $max_price; ?>" step="100" class="form-range"
                    oninput="document.getElementById('priceLabel').textContent = 'Rs. ' + this.value + '.00'">
                <div class="price-labels">
                    <span id="priceLabel">Rs. <?php echo number_format($max_price, 2); ?></span>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Apply Filter</button>
            </section>
        </aside>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <p class="mb-2 mb-md-0">
                    Showing <?php echo ($totalItems > 0) ? ($offset + 1) : 0; ?> - 
                    <?php echo min($offset + $itemsPerPage, $totalItems); ?> of <?php echo $totalItems; ?> results
                </p>
                <div class="d-flex align-items-center gap-2">
                    <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="" <?php if ($sort === '') echo 'selected'; ?>>Best Match</option>
                        <option value="low_high" <?php if ($sort === 'low_high') echo 'selected'; ?>>Price: Low to High</option>
                        <option value="high_low" <?php if ($sort === 'high_low') echo 'selected'; ?>>Price: High to Low</option>
                        <option value="newest" <?php if ($sort === 'newest') echo 'selected'; ?>>Newest</option>
                    </select>
                    <?php
                    $gridUrl = buildUrl(array_merge($currentFilters, ['view' => 'grid']));
                    $listUrl = buildUrl(array_merge($currentFilters, ['view' => 'list']));
                    ?>
                    <a href="<?php echo $gridUrl; ?>" class="btn btn-outline-primary btn-sm <?php echo ($viewMode === 'grid') ? 'active' : ''; ?>">
                        <i class="bi bi-grid-fill"></i>
                    </a>
                    <a href="<?php echo $listUrl; ?>" class="btn btn-outline-primary btn-sm <?php echo ($viewMode === 'list') ? 'active' : ''; ?>">
                        <i class="bi bi-list"></i>
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <?php
                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $imagePaths = explode(',', $row['image_path']);
                        $firstImage = trim($imagePaths[0]);
                        $imageSrc = (strpos($firstImage, 'uploads/') === 0) ? $firstImage : 'uploads/' . $firstImage;

                        $rating = isset($row['rating']) ? (float)$row['rating'] : 0;
                        $fullStars = floor($rating);
                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;

                        if ($viewMode === 'list'):
                ?>
                    <div class="col-12">
                        <a href="item-details.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                            <div class="list-view-card d-flex">
                                <div class="col-4 col-md-3">
                                    <img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid">
                                </div>
                                <div class="col-8 col-md-9 ps-3 d-flex flex-column justify-content-center">
                                    <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <div class="price mb-1">Rs. <?php echo number_format($row['price'], 2); ?></div>
                                    <div class="product-rating mb-1">
                                        <?php for ($i = 0; $i < $fullStars; $i++) echo '★'; ?>
                                        <?php if ($halfStar) echo '⯪'; ?>
                                        <?php for ($i = 0; $i < $emptyStars; $i++) echo '☆'; ?>
                                    </div>
                                    <div class="desc"><?php echo htmlspecialchars(substr($row['description'], 0, 80)); ?>...</div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                        else:
                ?>
                    <div class="col-6 col-md-3">
                        <a href="item-details.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                            <div class="medi-o-product-card text-center">
                                <img src="<?php echo htmlspecialchars($imageSrc); ?>" class="bs-img img-fluid mb-2"
                                     alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <h6 class="medi-o-product-title"><?php echo htmlspecialchars($row['name']); ?></h6>
                                <p class="medi-o-product-price mb-1">Rs. <?php echo number_format($row['price'], 2); ?></p>
                                <div class="text-warning">
                                    <?php for ($i = 0; $i < $fullStars; $i++) echo '★'; ?>
                                    <?php if ($halfStar) echo '⯪'; ?>
                                    <?php for ($i = 0; $i < $emptyStars; $i++) echo '☆'; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; endwhile; else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?php echo buildUrl(array_merge($currentFilters, ['page' => $page - 1])); ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo buildUrl(array_merge($currentFilters, ['page' => $i])); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="<?php echo buildUrl(array_merge($currentFilters, ['page' => $page + 1])); ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </form>
</section>

<!-- Footer -->
<?php include("components/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
