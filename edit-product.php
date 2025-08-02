<?php
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id");
if (!$result || $result->num_rows === 0) {
    echo "Product not found.";
    exit;
}
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Edit Product</h2>
        <form method="POST" action="product-actions.php" enctype="multipart/form-data" class="border p-4 bg-white rounded shadow-sm">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <?php
                $images = explode(',', $product['image_path']);
                foreach ($images as $index => $img): ?>
                    <?php
                        $img = trim($img);
                        $imgSrc = (strpos($img, 'uploads/') === 0) ? $img : 'uploads/' . $img;
                    ?>
                    <div class="d-inline-block position-relative mb-2 me-2 image-preview-wrapper" data-index="<?= $index ?>">
                        <img src="<?= htmlspecialchars($imgSrc) ?>" width="80" class="rounded">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 start-100 translate-middle remove-image-btn" data-index="<?= $index ?>" title="Remove">&times;</button>
                        <input type="hidden" name="existing_images[]" value="<?= htmlspecialchars($img) ?>">
                    </div>
                <?php endforeach; 
                
                ?>
                <input type="hidden" name="existing_images" value="<?= $product['image_path'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New Images (optional)</label>
                <input type="file" name="image[]" class="form-control" multiple>
            </div>
            <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
            <a href="admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script>
document.querySelectorAll('.remove-image-btn').forEach(function(button) {
    button.addEventListener('click', function () {
        const index = this.getAttribute('data-index');
        const wrapper = document.querySelector('.image-preview-wrapper[data-index="' + index + '"]');
        if (wrapper) wrapper.remove();
    });
});
</script>

</body>
</html>
