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
    <section id="dashboardSection" tabindex="0">
      <h2 class="section-title">Dashboard Overview</h2>
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card shadow-sm p-3">
            <div class="card-icon mb-2 text-center"><i class="bi bi-people-fill"></i></div>
            <h5>Total Users</h5>
            <p class="fs-4 fw-bold" id="totalUsers">1234</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm p-3">
            <div class="card-icon mb-2 text-center"><i class="bi bi-file-earmark-text-fill"></i></div>
            <h5>Total Prescriptions</h5>
            <p class="fs-4 fw-bold" id="totalPrescriptions">567</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm p-3">
            <div class="card-icon mb-2 text-center"><i class="bi bi-clock-history"></i></div>
            <h5>Pending Approvals</h5>
            <p class="fs-4 fw-bold" id="pendingApprovals">89</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm p-3">
            <div class="card-icon mb-2 text-center"><i class="bi bi-box-seam"></i></div>
            <h5>Total Products</h5>
            <p class="fs-4 fw-bold" id="totalProducts">350</p>
          </div>
        </div>
      </div>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card shadow-sm p-3">
            <div class="card-icon mb-2 text-center"><i class="bi bi-cart-fill"></i></div>
            <h5>Best-Selling Items</h5>
            <ul id="bestSellingList" class="list-group list-group-flush">
              <li class="list-group-item">Paracetamol</li>
              <li class="list-group-item">Ibuprofen</li>
              <li class="list-group-item">Vitamin C</li>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm p-3">
            <h5 class="mb-3 text-center">Prescription Status Chart</h5>
            <canvas id="statusChart" height="180" aria-label="Prescription status chart" role="img"></canvas>
          </div>
        </div>
      </div>
    </section>

<!-- User Management Section -->
<section id="userManagementSection" class="d-none" tabindex="0">
  <h2 class="section-title">User Management</h2>
  <p>View all registered users here .</p>

  <!-- Search Form
  <form method="GET" action="admin.php"> 
   <div class="d-flex">
      <button type="submit" class="btn btn-secondary mb-3 me-2">Search</button>
      
      <button type="button" class="btn btn-danger reset-a mb-3" onclick="window.location.href='admin.php?section=userManagement'">Reset</button>
    </div>
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
          
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>




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
                <?php
                $result = $conn->query("SELECT * FROM prescriptions"); // Fetch prescriptions from the DB
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['prescription_id'] ?></td>
                    <td>
                        <a href="path/to/prescription-image.jpg" download>Download Prescription File</a>
                    </td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['customer_phone']) ?></td>
                    <td><?= htmlspecialchars($row['customer_address']) ?></td>
                    <td><?= htmlspecialchars($row['customer_email']) ?></td>
                    <td>
                        <!-- Action Button to mark as Ready to Pick -->
                        <a href="prescription-management.php?action=readyToPick&id=<?= $row['prescription_id'] ?>" 
                           class="btn btn-sm btn-success" 
                           title="Mark as Ready to Pick">
                           <i class="bi bi-check-circle"></i> Ready to Pick
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>






<!-- Product Management UI -->
<section id="productManagementSection" class="mt-5">
  <h2 class="section-title">Product Management</h2>
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">Add New Product</button>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
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
          <td>$<?= number_format($row['price'], 2) ?></td>
          <td><?= $row['stock'] ?></td>
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
            <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
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
        <input type="file" name="images[]" multiple class="form-control">
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>


    <!-- Contact Inquiry Management -->
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
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>C001</td>
              <td>Emma Watson</td>
              <td>emma@example.com</td>
              <td>Question about product availability.</td>
              <td><span class="badge bg-warning text-dark">Pending</span></td>
              <td>
                <button class="btn btn-sm btn-success" title="Mark as Responded"><i class="bi bi-check-lg"></i></button>
              </td>
            </tr>
            <tr>
              <td>C002</td>
              <td>James Brown</td>
              <td>james@example.com</td>
              <td>Request for refund process.</td>
              <td><span class="badge bg-success">Responded</span></td>
              <td>
                <button class="btn btn-sm btn-info" title="View Reply"><i class="bi bi-envelope-open"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>


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