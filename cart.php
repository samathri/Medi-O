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

    



    <!-- Cart Page Section -->
    <section class="cart-page py-5">
        <div class="container">
            <h2 class="cart-title text-center mb-4">Your Cart</h2>

            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <!-- Cart Item 1 -->
                    <div class="cart-item d-flex justify-content-between align-items-center mb-4">
                        <div class="cart-item-info d-flex align-items-center">
                            <!-- Product Image -->
                            <img src="images/aknicarelotion 1.png" alt="Product Image" class="cart-item-img" />
                            <div class="cart-item-details ms-3">
                                <h4 class="cart-item-title">Aknicare Cleanser</h4>
                                <p class="cart-item-price">Rs. 2,910.00</p>
                            </div>
                        </div>
                        <!-- Quantity and Remove -->
                        <div class="cart-item-actions d-flex align-items-center">
                            <input type="number" class="form-control cart-item-quantity" value="1" min="1" max="10" />
                            <button class="btn btn-outline-danger ms-3">Remove</button>
                        </div>
                    </div>

                    <!-- Cart Item 2 -->
                    <div class="cart-item d-flex justify-content-between align-items-center mb-4">
                        <div class="cart-item-info d-flex align-items-center">
                            <!-- Product Image -->
                            <img src="images/ACNE-AID 1.png" alt="Product Image" class="cart-item-img" />
                            <div class="cart-item-details ms-3">
                                <h4 class="cart-item-title">Acne-Aid Bar</h4>
                                <p class="cart-item-price">Rs. 2,200.00</p>
                            </div>
                        </div>
                        <!-- Quantity and Remove -->
                        <div class="cart-item-actions d-flex align-items-center">
                            <input type="number" class="form-control cart-item-quantity" value="1" min="1" max="10" />
                            <button class="btn btn-outline-danger ms-3">Remove</button>
                        </div>
                    </div>

                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary p-4 border rounded">
                        <h4 class="cart-summary-title">Cart Summary</h4>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="mb-0">Subtotal:</p>
                            <p class="mb-0">Rs. 5,110.00</p>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="mb-0">Shipping:</p>
                            <p class="mb-0">Rs. 150.00</p>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <p class="mb-0">Total:</p>
                            <p class="mb-0">Rs. 5,260.00</p>
                        </div>
                        <!-- Link Buttons -->
                        <a href="checkout.php" class="btn btn-primary w-100 mb-3">Checkout</a>
                        <a href="shop.php" class="btn btn-outline-primary w-100">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Best Selling Section -->

    <section class="medi-o-best-selling-section text-center py-5">
        <div class="container medi-o-container">
            <h2 class="text-primary fw-semibold mb-5">BEST SELLING</h2>

            <!-- Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php
include 'includes/db.php'; // Adjust the path if needed
?>

    <?php
    // Fetch best-selling items from the database
    $sql = "SELECT * FROM best_selling_items ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $images = explode(',', $row['image_paths']);
            $firstImage = htmlspecialchars($images[0]);
            $title = htmlspecialchars($row['item_name']);
            $price = number_format($row['price'], 2);
            $rating = floatval($row['rating']);

            // Star rendering
            $filledStars = floor($rating);
            $halfStar = ($rating - $filledStars) >= 0.5;
            $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
            ob_start();
            for ($i = 0; $i < $filledStars; $i++) echo '★ ';
            if ($halfStar) echo '☆ ';
            for ($i = 0; $i < $emptyStars; $i++) echo '☆ ';
            $starsHtml = trim(ob_get_clean());
    ?>
        <div class="swiper-slide">
            <div class="medi-o-product-card text-center">
                <img src="<?= $firstImage ?>" class="bs-img img-fluid mb-3" alt="<?= $title ?>">
                <h6 class="medi-o-product-title"><?= $title ?></h6>
                <p class="medi-o-product-price mb-1">Rs. <?= $price ?></p>
                <div class="text-warning"><?= $starsHtml ?></div>
            </div>
        </div>
    <?php
        endwhile;
    else:
        echo "<div class='swiper-slide'>No best-selling items found.</div>";
    endif;
    ?>
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