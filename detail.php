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

    


    <!-- Item Details Section -->
    <section class="item-details-section py-5">
        <div class="container">
            <div class="row">
                <!-- Product Images -->
                <div class="col-md-6 col-12 mb-4 mb-md-0">
                    <div class="item-images">
                        <!-- Main Image (Larger image) -->
                        <img src="images/aknicarelotion 1.png" alt="Product Image" class="img-fluid mb-3"
                            id="mainImage" />
                        <div class="item-thumbnails d-flex justify-content-start">
                            <!-- Thumbnails (Smaller size) -->
                            <img src="images/aknicarelotion 1.png" alt="Thumbnail 1" class="img-fluid mb-2 thumbnail"
                                onclick="changeImage(this)" />
                            <img src="images/ACNE-AID 1.png" alt="Thumbnail 2" class="img-fluid mb-2 thumbnail"
                                onclick="changeImage(this)" />
                            <img src="images/Isocal-600x600.png" alt="Thumbnail 3" class="img-fluid mb-2 thumbnail"
                                onclick="changeImage(this)" />
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="col-md-6 col-12">
                    <div class="item-info">
                        <h2 class="item-title">Product Name</h2>
                        <p class="item-price">Rs. 2,910.00</p>

                        <!-- Product Rating -->
                        <div class="item-rating mb-3">
                            <span>★ ★ ★ ★ ☆</span>
                        </div>

                        <!-- Product Description -->
                        <p class="item-desc mt-3">
                            This is a detailed description of the product. It provides information about the product
                            features, benefits, and uses.
                        </p>

                        <!-- Quantity and Add to Cart -->
                        <div class="item-quantity mt-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" id="quantity" class="form-control" value="1" min="1" max="10" />
                        </div>

                        <!-- Buttons: Add to Cart and Buy Now -->
                        <div class="d-flex justify-content-start mt-3">
                            <button class="btn btn-primary me-3">Add to Cart</button>
                            <button class="btn btn-success">Buy Now</button>
                        </div>
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
                    <!-- Product Card 1 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Isocal-600x600.png" class="bs-img img-fluid mb-3" alt="Ortho Shield">
                            <h6 class="medi-o-product-title">ISOCAL POWDER 425g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/Siddhalepa-Balm-50g 1.png" class="bs-img img-fluid mb-3"
                                alt="Siddhalepa Balm">
                            <h6 class="medi-o-product-title">SIDDHALEPA BALM 50G</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/ACNE-AID 1.png" class="bs-img img-fluid mb-3" alt="Acne-Aid Bar">
                            <h6 class="medi-o-product-title">ACNE-AID BAR 100g</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="swiper-slide">
                        <div class="medi-o-product-card text-center">
                            <img src="images/aknicarelotion 1.png" class="bs-img img-fluid mb-3"
                                alt="Aknicare Cleanser">
                            <h6 class="medi-o-product-title">AKNICARE CLEANSER 200ml</h6>
                            <p class="medi-o-product-price mb-1">Rs. 2,910.00</p>
                            <div class="text-warning">★ ★ ★ ☆ ☆</div>
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