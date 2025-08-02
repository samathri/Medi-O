<?php
include 'includes/db.php';

// Handle Insert
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    
    // Image upload
    $image_path = '';
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category, $image_path);
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

<!-- Product Management UI -->
<section id="productManagementSection" class="mt-5" tabindex="0">
  <h2 class="section-title">Product Management</h2>
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">Add New Product</button>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td>$<?= number_format($row['price'], 2) ?></td>
          <td><?= $row['stock'] ?></td>
          <td>
            <?php if ($row['image_path']): ?>
              <img src="<?= $row['image_path'] ?>" width="50">
            <?php else: ?>
              No image
            <?php endif; ?>
          </td>
          <td>
            <!-- Update Button -->
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                    data-bs-target="#updateModal<?= $row['id'] ?>"><i class="bi bi-pencil"></i></button>
            <!-- Delete -->
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>

        <!-- Update Modal -->
        <div class="modal fade" id="updateModal<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="text" name="name" class="form-control mb-2" placeholder="Title" value="<?= htmlspecialchars($row['name']) ?>" required>
                <textarea name="description" class="form-control mb-2" placeholder="Description"><?= htmlspecialchars($row['description']) ?></textarea>
                <input type="number" step="0.01" name="price" class="form-control mb-2" value="<?= $row['price'] ?>" required>
                <input type="number" name="stock" class="form-control mb-2" value="<?= $row['stock'] ?>" required>
                <input type="text" name="category" class="form-control mb-2" value="<?= htmlspecialchars($row['category']) ?>" required>
                <input type="file" name="image" class="form-control">
              </div>
              <div class="modal-footer">
                <button type="submit" name="update_product" class="btn btn-success">Update</button>
              </div>
            </form>
          </div>
        </div>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="name" class="form-control mb-2" placeholder="Title" required>
        <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
        <input type="number" step="0.01" name="price" class="form-control mb-2" placeholder="Price" required>
        <input type="number" name="stock" class="form-control mb-2" placeholder="Stock" required>
        <input type="text" name="category" class="form-control mb-2" placeholder="Category" required>
        <input type="file" name="image" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>
