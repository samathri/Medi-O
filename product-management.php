<?php
include 'includes/db.php';

// Handle Insert
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Handle multiple images
    $image_paths = [];
    if (!empty($_FILES['images']['name'][0])) {
        $target_dir = "uploads/";
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $filename = basename($_FILES['images']['name'][$key]);
            $target_file = $target_dir . $filename;
            move_uploaded_file($tmp_name, $target_file);
            $image_paths[] = $target_file;
        }
    }

    $image_path_str = implode(',', $image_paths);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category, $image_path_str);
    $stmt->execute();
    header("Location: admin.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// Handle Update
if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    $image_sql = "";
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        $image_sql = ", image_path='$image_path'";
    }

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock', category='$category' $image_sql WHERE id=$id";
    $conn->query($sql);
    header("Location: admin.php");
    exit;
}
?>
