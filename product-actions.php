<?php
include 'includes/db.php';

// Insert or Update
if (isset($_POST['save_product'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];

  if ($id) {
    // Update
    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=? WHERE id=?");
    $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);
  } else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdi", $name, $category, $price, $stock);
  }

  $stmt->execute();
  $stmt->close();
  header("Location: admin.php");
  exit;
}

// Delete
if (isset($_POST['delete_product'])) {
  $id = $_POST['delete_id'];
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
  header("Location: admin.php");
  exit;
}
