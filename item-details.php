<?php
session_start();
include 'includes/db.php';

// Get product ID from URL
if (!isset($_GET['id'])) {
    die("Product ID not specified.");
}
$productId = intval($_GET['id']);

// Fetch product from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Process data
$productName = htmlspecialchars($product['name']);
$description = nl2br(htmlspecialchars($product['description']));
$price = number_format($product['price'], 2);
$rating = floatval($product['rating']);
$stock = intval($product['stock']);
$imagePaths = isset($product['image_path']) ? explode(',', $product['image_path']) : [];

// Star rating rendering
$filledStars = floor($rating);
$halfStar = ($rating - $filledStars) >= 0.5;
$emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
$starsHTML = str_repeat("★ ", $filledStars) . ($halfStar ? "☆ " : "") . str_repeat("☆ ", $emptyStars);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medi-O</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<!-- Header -->
<?php include("components/header.php"); ?>

<!-- Item Details Section -->
<section class="item-details-section py-5">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6 col-12 mb-4 mb-md-0">
                <div class="item-images">
                    <?php if (!empty($imagePaths[0])): ?>
                        <img src="<?= htmlspecialchars(trim($imagePaths[0])) ?>" alt="Product Image" class="img-fluid mb-3" id="mainImage" />
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>

                    <div class="item-thumbnails d-flex flex-wrap gap-2">
                        <?php foreach ($imagePaths as $path): ?>
                            <?php $cleanedPath = htmlspecialchars(trim($path)); ?>
                            <?php if (!empty($cleanedPath)): ?>
                                <img src="<?= $cleanedPath ?>" alt="Thumbnail" class="img-fluid thumbnail" style="width: 70px; cursor: pointer;" onclick="changeImage(this)" />
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6 col-12">
                <div class="item-info">
                    <h2 class="item-title"><?= $productName ?></h2>
                    <p class="item-price">Rs. <?= $price ?></p>

                    <!-- Rating -->
                    <div class="item-rating mb-3 text-warning">
                        <span><?= $starsHTML ?></span>
                    </div>

                    <!-- Stock Info -->
                    <p><strong>Stock:</strong> <?= $stock > 0 ? $stock : "<span class='text-danger'>Out of Stock</span>" ?></p>

                    <!-- Description -->
                    <p class="item-desc mt-3"><?= $description ?></p>

                    <!-- Quantity -->
                    <div class="item-quantity mt-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" class="form-control" value="1" min="1" max="<?= $stock ?>" <?= $stock <= 0 ? 'disabled' : '' ?> />
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-start mt-3">
                        <button class="btn btn-primary me-3" <?= $stock <= 0 ? 'disabled' : '' ?>>Add to Cart</button>
                        <button class="btn btn-success" <?= $stock <= 0 ? 'disabled' : '' ?>>Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Footer -->
<?php include("components/footer.php"); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
function changeImage(el) {
    document.getElementById("mainImage").src = el.src;
}
</script>
</body>
</html>
