<?php
session_start();
?>
<?php include 'product-management.php'; ?>
<?php include 'user-management.php'; ?>
<?php include 'prescription-management.php'; ?>

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



  <!-- Sidebar
  <nav id="sidebar" aria-label="Admin Sidebar Navigation">
    <div class="p-4 ">
      <h3 class="text-white mb-4 text-center">Medi-O Admin</h3>
      <ul class="nav flex-column" id="sidebarMenu">
        <li class="nav-item"><a class="nav-link active" href="#dashboardSection"><i
              class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="#userManagementSection"><i class="bi bi-people me-2"></i>User
            Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#prescriptionManagementSection"><i
              class="bi bi-file-earmark-medical me-2"></i>Prescription Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#pharmacistManagementSection"><i
              class="bi bi-person-badge me-2"></i>Pharmacist Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#productManagementSection"><i
              class="bi bi-basket3 me-2"></i>Product Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#bestSellingSection"><i
              class="bi bi-star-fill me-2"></i>Best-Selling Items</a></li>
        <li class="nav-item"><a class="nav-link" href="#cmsSection"><i class="bi bi-file-text me-2"></i>CMS / Content
            Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#contactInquirySection"><i
              class="bi bi-envelope me-2"></i>Contact Inquiries</a></li>
        <li class="nav-item"><a class="nav-link" href="#adminProfileSection"><i
              class="bi bi-person-circle me-2"></i>Admin Profile & Security</a></li>
        <li class="nav-item mt-3"><button id="logoutBtn" class="btn btn-danger w-100"><i
              class="bi bi-box-arrow-right me-2"></i>Logout</button></li>
      </ul>
    </div>
  </nav> -->

  <!-- Page Content -->
  <button id="sidebarCollapse" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>


  <!-- Sidebar -->
  <nav id="sidebar" aria-label="Admin Sidebar Navigation">
    <div class="p-4 ">
      <h3 class="text-white mb-4 text-center">Medi-O Admin</h3>
      <ul class="nav flex-column" id="sidebarMenu">
        <li class="nav-item"><a class="nav-link active" href="#dashboardSection"><i
                            class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                
        <li class="nav-item"><a class="nav-link" href="#userManagementSection"><i class="bi bi-people me-2"></i>User
            Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#prescriptionManagementSection"><i
              class="bi bi-file-earmark-medical me-2"></i>Prescription Management</a></li>
        
        <li class="nav-item"><a class="nav-link" href="#productManagementSection"><i
              class="bi bi-basket3 me-2"></i>Product Management</a></li>
        
        
        <li class="nav-item"><a class="nav-link" href="#contactInquirySection"><i
              class="bi bi-envelope me-2"></i>Contact Inquiries</a></li>
        
        <li class="nav-item mt-3">
          <a href="logout.php" id="logoutBtn" class="btn btn-danger w-100 text-decoration-none">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
          </a>
        </li>

      </ul>
    </div>
  </nav>

  <!-- Page Content -->
  <button id="sidebarCollapse" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <main id="content" tabindex="-1">
    <!-- Dashboard Overview -->
<?php
// Include the database connection
include 'includes/db.php';

// Fetch the count of users
$sql_users = "SELECT COUNT(id) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch the count of prescriptions
$sql_prescriptions = "SELECT COUNT(id) AS total_prescriptions FROM prescriptions_2";
$result_prescriptions = $conn->query($sql_prescriptions);
$total_prescriptions = $result_prescriptions->fetch_assoc()['total_prescriptions'];

// Fetch the count of products
$sql_products = "SELECT COUNT(id) AS total_products FROM products";
$result_products = $conn->query($sql_products);
$total_products = $result_products->fetch_assoc()['total_products'];

$conn->close();
?>

<section id="dashboardSection" tabindex="0">
    <h2 class="section-title">Dashboard Overview</h2>

    <!-- Row for Key Metrics (Total Users, Total Prescriptions, Total Products) -->
    <div class="row g-3 mb-4">
        <!-- Total Users -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm p-4 h-100">
                <div class="card-icon mb-3 text-center">
                    <i class="bi bi-people-fill fs-2 text-primary"></i>
                </div>
                <h5 class="text-center">Total Users</h5>
                <p class="fs-4 fw-bold text-center" id="totalUsers"><?= number_format($total_users) ?></p>
            </div>
        </div>

        <!-- Total Prescriptions -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm p-4 h-100">
                <div class="card-icon mb-3 text-center">
                    <i class="bi bi-file-earmark-text-fill fs-2 text-success"></i>
                </div>
                <h5 class="text-center">Total Prescriptions</h5>
                <p class="fs-4 fw-bold text-center" id="totalPrescriptions"><?= number_format($total_prescriptions) ?></p>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm p-4 h-100">
                <div class="card-icon mb-3 text-center">
                    <i class="bi bi-box-seam fs-2 text-danger"></i>
                </div>
                <h5 class="text-center">Total Products</h5>
                <p class="fs-4 fw-bold text-center" id="totalProducts"><?= number_format($total_products) ?></p>
            </div>
        </div>
    </div>
    </div>



    <!-- best selling  -->


<?php
// Include database connection
include 'includes/db.php';

$editMode = false;
$editItem = null;

// Handle form submission for add or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['item_name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $itemId = isset($_POST['item_id']) ? intval($_POST['item_id']) : null;

    $uploadedImagePaths = [];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $uploadDir = 'uploads/images/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Upload images if any
    $errors = [];
    if (!empty($_FILES['image_paths']['name'][0])) {
        for ($i = 0; $i < count($_FILES['image_paths']['name']); $i++) {
            $imageTmpName = $_FILES['image_paths']['tmp_name'][$i];
            $imageName = basename($_FILES['image_paths']['name'][$i]);
            $imageSize = $_FILES['image_paths']['size'][$i];
            $imageType = $_FILES['image_paths']['type'][$i];

            if (!in_array($imageType, $allowedTypes)) {
                $errors[] = "Invalid file type: $imageName.";
                continue;
            }

            if ($imageSize > $maxFileSize) {
                $errors[] = "File too large: $imageName.";
                continue;
            }

            $uniqueFileName = uniqid() . '-' . $imageName;
            $uploadFilePath = $uploadDir . $uniqueFileName;
            if (move_uploaded_file($imageTmpName, $uploadFilePath)) {
                $uploadedImagePaths[] = $uploadFilePath;
            } else {
                $errors[] = "Upload failed: $imageName.";
            }
        }
    }

    if (empty($errors)) {
        $imagePaths = !empty($uploadedImagePaths) ? implode(',', $uploadedImagePaths) : null;

        if ($itemId) {
            // UPDATE
            if ($imagePaths) {
                $sql = "UPDATE best_selling_items SET item_name = ?, image_paths = ?, price = ?, rating = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdii", $itemName, $imagePaths, $price, $rating, $itemId);
            } else {
                $sql = "UPDATE best_selling_items SET item_name = ?, price = ?, rating = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sdii", $itemName, $price, $rating, $itemId);
            }

            if ($stmt->execute()) {
                echo "<div class='alert alert-success' id='success-message'>Item updated successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Update failed: " . $conn->error . "</div>";
            }
        } else {
            // INSERT
            $sql = "INSERT INTO best_selling_items (item_name, image_paths, price, rating, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdi", $itemName, $imagePaths, $price, $rating);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success' id='success-message'>Item added successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Add failed: " . $conn->error . "</div>";
            }
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $sql = "DELETE FROM best_selling_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    echo "<div class='alert alert-success' id='success-message'>Item deleted.</div>";
}

// Handle edit - fetch item
if (isset($_GET['edit_id'])) {
    $editMode = true;
    $editId = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM best_selling_items WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $resultEdit = $stmt->get_result();
    $editItem = $resultEdit->fetch_assoc();
}

// Fetch all items
$result = $conn->query("SELECT * FROM best_selling_items ORDER BY created_at DESC");
?>

<style>
    .star {
        font-size: 24px;
        color: #d3d3d3;
    }
    .star.filled {
        color: #FFD700;
    }
</style>

<h2><?= $editMode ? "Edit" : "Add" ?> Best-Selling Item</h2>

<form method="POST" enctype="multipart/form-data" class="mb-5">
    <?php if ($editMode): ?>
        <input type="hidden" name="item_id" value="<?= $editItem['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
        <label>Item Name</label>
        <input type="text" name="item_name" class="form-control" value="<?= $editMode ? htmlspecialchars($editItem['item_name']) : '' ?>" required>
    </div>

    <div class="form-group">
        <label>Images <?= $editMode ? "(optional)" : "(required)" ?></label>
        <input type="file" name="image_paths[]" class="form-control" multiple <?= $editMode ? "" : "required" ?> accept="image/*">
    </div>

    <div class="form-group">
        <label>Price (Rs.)</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?= $editMode ? $editItem['price'] : '' ?>" required>
    </div>

    <div class="form-group">
        <label>Rating (0-5)</label>
        <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" value="<?= $editMode ? $editItem['rating'] : '' ?>" required>
    </div>

    <div class="d-flex gap-2 align-items-center mt-3">
    <button type="submit" style="width: fit-content;" class="btn btn-<?= $editMode ? 'warning' : 'primary' ?> px-4 py-2" >
        <?= $editMode ? "Update Item" : "Add Item" ?>
    </button>
    
    <?php if ($editMode): ?>
        <a href="?" class="btn btn-secondary" style="border-radius: 50px; margin-top: 1%;">
            Cancel
        </a>
    <?php endif; ?>
</div>

</form>

<!-- Items Table -->
<h4>Best-Selling Items List</h4>
<table class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th>Image</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Rating</th>
            <th>Added On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php
                            $images = explode(',', $row['image_paths']);
                            $firstImage = htmlspecialchars($images[0]);
                        ?>
                        <img src="<?= $firstImage ?>" alt="Image" style="max-width: 50px;" class="img-fluid">
                    </td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td>Rs. <?= number_format($row['price'], 2) ?></td>
                    <td>
                        <?php
                            $rating = $row['rating'];
                            $filled = floor($rating);
                            $half = ($rating - $filled) >= 0.5;
                            $empty = 5 - $filled - ($half ? 1 : 0);
                            for ($i = 0; $i < $filled; $i++) echo '<span class="star filled">★</span>';
                            if ($half) echo '<span class="star">☆</span>';
                            for ($i = 0; $i < $empty; $i++) echo '<span class="star">☆</span>';
                        ?>
                    </td>
                    <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>
                    <td>
                        <a href="?edit_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this item?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No items found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    // This script will hide success messages after 10 seconds.
    window.onload = function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000); // 10 seconds
        }
    };
</script>

</section>




<!-- User Management Section -->
<?php
include 'includes/db.php';

// Handle Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Delete the user from the database
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: admin.php?section=userManagement"); // Redirect to user management section
    exit;
}

// Handle User Search
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchSql = " WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
} else {
    $searchSql = '';
}

// Fetch Users (with optional search filter)
$sql = "SELECT * FROM users" . $searchSql . " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!-- User Management Section -->
<section id="userManagementSection" class="d-none" tabindex="0">
  <h2 class="section-title">User Management</h2>
  <p>View and manage all registered users.</p>

  <!-- Search Form -->
  <!-- <form method="GET">
    <input type="text" class="form-control mb-3" name="search" placeholder="Search users..." value="<?= htmlspecialchars($searchQuery) ?>" aria-label="Search users">
    <button type="submit" class="btn btn-secondary mb-3">Search</button>
  </form> -->

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td><?= htmlspecialchars($row['address']) ?></td>
          <td>
            <!-- Delete Button -->
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
              <i class="bi bi-trash"></i> Delete
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>



<?php
include 'includes/db.php'; // Database connection

// Fetch Prescription and User Details
$sql = "
    SELECT p.id AS prescription_id, p.file_path, p.status, 
           u.name AS customer_name, u.phone AS customer_phone, 
           u.address AS customer_address, u.email AS customer_email 
    FROM `prescriptions_2` p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error in fetching prescriptions: " . $conn->error;
}
?>

<!-- Prescription Management Section -->
<section id="prescriptionManagementSection" class="d-none" tabindex="0">
    <h2 class="section-title">Prescription Management</h2>
    
    <!-- Table for displaying prescriptions -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Prescription Image</th>
                    <th>Customer Name</th>
                    <th>Customer Phone</th>
                    <th>Customer Address</th>
                    <th>Customer Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['prescription_id']) ?></td>
                            <td>
                                <a href="uploads/prescriptions/<?= htmlspecialchars($row['file_path']) ?>" download>Download Prescription File</a>
                            </td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['customer_phone']) ?></td>
                            <td><?= htmlspecialchars($row['customer_address']) ?></td>
                            <td><?= htmlspecialchars($row['customer_email']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-success" title="Mark as Ready to Pick">
                                    <i class="bi bi-check-circle"></i> Ready to Pick
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No prescriptions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>



<!-- Product Management UI -->
<section id="productManagementSection" class="mt-5">
  <h2 class="section-title">Product Management</h2>
  <button class="btn btn-primary mb-3" style="width:fit-content" data-bs-toggle="modal" data-bs-target="#productModal">Add New Product</button>

  <div class="table-responsive">
    <table class="table table-hover ">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Rating</th> <!-- New Column -->
          <th>Images</th>
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
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td>Rs. <?= number_format($row['price'], 2) ?></td>
          <td><?= $row['stock'] ?></td>
          <td><?= $row['rating'] ?></td> <!-- New Rating Value -->
          <td>
            <?php
            $images = explode(',', $row['image_path']);
            foreach ($images as $img) {
              $img = trim($img);
              if ($img) {
                  $imgSrc = (strpos($img, 'uploads/') === 0) ? $img : 'uploads/' . $img;
                  echo "<img src='" . htmlspecialchars($imgSrc) . "' width='50' class='me-1 mb-1'>";
              }
          }
            ?>
          </td>
          <td>
            <!-- Update Button -->
            <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning ">
              <i class="bi bi-pencil"></i>
            </a>

            <!-- Delete -->
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>

        <!-- Update Modal -->
        <div class="modal fade" id="updateModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
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
                <input type="number" name="rating" class="form-control mb-2" min="0" max="5" step="0.1" value="<?= $row['rating'] ?>" required>
                <input type="file" name="image" class="form-control mb-2">
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
        <input type="number" name="rating" class="form-control mb-2" min="0" max="5" step="0.1" placeholder="Rating (1–5)" required>
        <input type="file" name="images[]" multiple class="form-control">
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>



    <!-- Contact Inquiry Management -->
    <?php
// Include the database connection file
include 'includes/db.php';

// Fetch inquiries from the contact_messages table
$sql = "SELECT * FROM contact_messages ORDER BY id DESC"; // Fetching all records, ordered by ID
$result = $conn->query($sql);
?>

<!-- Contact Inquiry Management Section -->
<section id="contactInquirySection" class="d-none" tabindex="0">
    <h2 class="section-title">Contact Inquiry Management</h2>
    <p>View and respond to contact form submissions.</p>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Inquiry ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No inquiries found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
// Close the database connection
$conn->close();
?>



    <!-- Admin Profile & Security -->
    <section id="adminProfileSection" class="d-none" tabindex="0">
      <h2 class="section-title">Admin Profile & Security</h2>

      <form id="changePasswordForm" class="mb-4" aria-label="Change password form">
        <div class="mb-3">
          <label for="currentPassword" class="form-label">Current Password</label>
          <input type="password" class="form-control" id="currentPassword" required />
        </div>
        <div class="mb-3">
          <label for="newPassword" class="form-label">New Password</label>
          <input type="password" class="form-control" id="newPassword" required />
        </div>
        <div class="mb-3">
          <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
          <input type="password" class="form-control" id="confirmNewPassword" required />
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
      </form>

      <h3>Login Activity</h3>
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Date/Time</th>
            <th>IP Address</th>
            <th>Browser</th>
            <th>Location</th>
          </tr>
        </thead>
        <tbody id="loginActivityTable">
          <tr>
            <td>2025-07-30 15:45</td>
            <td>192.168.0.101</td>
            <td>Chrome</td>
            <td>New York, USA</td>
          </tr>
          <tr>
            <td>2025-07-29 09:12</td>
            <td>192.168.0.95</td>
            <td>Firefox</td>
            <td>Los Angeles, USA</td>
          </tr>
        </tbody>
      </table>

      <h3 class="mt-4">Pending Prescription Approvals</h3>
      <p id="pendingApprovalCount" class="fs-5 text-danger">Loading...</p>
    </section>
  </main>












  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/script.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const sidebarLinks = document.querySelectorAll('#sidebarMenu .nav-link');
      const sections = document.querySelectorAll('main > section');

      function hideAllSections() {
        sections.forEach(sec => sec.classList.add('d-none'));
      }

      sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault();

          // Remove active class from all links
          sidebarLinks.forEach(l => l.classList.remove('active'));
          // Add active class to clicked link
          link.classList.add('active');

          // Hide all sections
          hideAllSections();

          // Get the target section ID from href
          const targetId = link.getAttribute('href').substring(1); // remove #

          // Show the targeted section
          const targetSection = document.getElementById(targetId);
          if (targetSection) {
            targetSection.classList.remove('d-none');
            // Optionally focus for accessibility
            targetSection.focus();
          }
        });
      });

      // Optionally: Show dashboard by default
      hideAllSections();
      const defaultSection = document.getElementById('dashboardSection');
      if (defaultSection) {
        defaultSection.classList.remove('d-none');
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('prescriptionSearch');
      const statusFilter = document.getElementById('statusFilter');
      const clearBtn = document.getElementById('clearFilters');
      const tableBody = document.getElementById('prescriptionTableBody');

      function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        Array.from(tableBody.rows).forEach(row => {
          const id = row.cells[0].textContent.toLowerCase();
          const user = row.cells[1].textContent.toLowerCase();
          const statusBadge = row.cells[4].textContent.toLowerCase();

          const matchesSearch = id.includes(searchValue) || user.includes(searchValue) || statusBadge.includes(searchValue);
          const matchesStatus = statusValue === '' || statusBadge.includes(statusValue.toLowerCase());

          if (matchesSearch && matchesStatus) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }

      searchInput.addEventListener('input', filterTable);
      statusFilter.addEventListener('change', filterTable);

      clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        statusFilter.value = '';
        filterTable();
      });
    });


  </script>




</body>

</html>