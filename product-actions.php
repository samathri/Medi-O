<?php
include 'includes/db.php';

if (isset($_POST['update_product'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = $conn->real_escape_string($_POST['category']);
    $existingImages = $_POST['existing_images'];

    $imagePaths = [];

    // Handle new uploads
    if (!empty($_FILES['image']['name'][0])) {
        foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['image']['name'][$key]);
            $targetPath = 'uploads/' . $fileName;
            move_uploaded_file($tmpName, $targetPath);
            $imagePaths[] = $fileName;
        }
    } else {
        // No new image uploaded; use existing
        $imagePaths = explode(',', $existingImages);
    }

    $finalImagePath = implode(',', $imagePaths);

    $updateQuery = "UPDATE products SET 
        name='$name',
        description='$description',
        price=$price,
        stock=$stock,
        category='$category',
        image_path='$finalImagePath'
        WHERE id=$id";

    if ($conn->query($updateQuery)) {
        header("Location: admin.php?msg=updated");
    } else {
        echo "Error updating product: " . $conn->error;
    }
    exit;
}
?>
