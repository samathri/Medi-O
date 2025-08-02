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



    
 <!-- Sidebar -->
  <nav id="sidebar" aria-label="Admin Sidebar Navigation">
    <div class="p-4 ">
      <h3 class="text-white mb-4 text-center">Medi-O Admin</h3>
      <ul class="nav flex-column" id="sidebarMenu">
        <li class="nav-item"><a class="nav-link active" href="#dashboardSection"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="#userManagementSection"><i class="bi bi-people me-2"></i>User Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#prescriptionManagementSection"><i class="bi bi-file-earmark-medical me-2"></i>Prescription Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#pharmacistManagementSection"><i class="bi bi-person-badge me-2"></i>Pharmacist Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#productManagementSection"><i class="bi bi-basket3 me-2"></i>Product Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#bestSellingSection"><i class="bi bi-star-fill me-2"></i>Best-Selling Items</a></li>
        <li class="nav-item"><a class="nav-link" href="#cmsSection"><i class="bi bi-file-text me-2"></i>CMS / Content Management</a></li>
        <li class="nav-item"><a class="nav-link" href="#contactInquirySection"><i class="bi bi-envelope me-2"></i>Contact Inquiries</a></li>
        <li class="nav-item"><a class="nav-link" href="#adminProfileSection"><i class="bi bi-person-circle me-2"></i>Admin Profile & Security</a></li>
        <li class="nav-item mt-3"><button id="logoutBtn" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></li>
      </ul>
    </div>
  </nav>

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
                <li class="nav-item"><a class="nav-link" href="#userManagementSection"><i
                            class="bi bi-people me-2"></i>User Management</a></li>
                <li class="nav-item"><a class="nav-link" href="#prescriptionManagementSection"><i
                            class="bi bi-file-earmark-medical me-2"></i>Prescription Management</a></li>
                <li class="nav-item"><a class="nav-link" href="#pharmacistManagementSection"><i
                            class="bi bi-person-badge me-2"></i>Pharmacist Management</a></li>
                <li class="nav-item"><a class="nav-link" href="#productManagementSection"><i
                            class="bi bi-basket3 me-2"></i>Product Management</a></li>
                <li class="nav-item"><a class="nav-link" href="#bestSellingSection"><i
                            class="bi bi-star-fill me-2"></i>Best-Selling Items</a></li>
                <li class="nav-item"><a class="nav-link" href="#cmsSection"><i class="bi bi-file-text me-2"></i>CMS /
                        Content Management</a></li>
                <li class="nav-item"><a class="nav-link" href="#contactInquirySection"><i
                            class="bi bi-envelope me-2"></i>Contact Inquiries</a></li>
                <li class="nav-item"><a class="nav-link" href="#adminProfileSection"><i
                            class="bi bi-person-circle me-2"></i>Admin Profile & Security</a></li>
                <li class="nav-item mt-3"><button id="logoutBtn" class="btn btn-danger w-100"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</button></li>
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

<!-- User Management -->
<section id="userManagementSection" class="d-none" tabindex="0">
  <h2 class="section-title">User Management</h2>
  <p>View and manage all registered users.</p>
  <input type="search" class="form-control mb-3" placeholder="Search users..." aria-label="Search users" />
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>U001</td>
          <td>Alice Johnson</td>
          <td>alice@example.com</td>
          <td><span class="badge bg-success">Active</span></td>
          <td>
            <button class="btn btn-sm btn-warning" title="Suspend"><i class="bi bi-person-x"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        <tr>
          <td>U002</td>
          <td>Bob Williams</td>
          <td>bob@example.com</td>
          <td><span class="badge bg-secondary">Suspended</span></td>
          <td>
            <button class="btn btn-sm btn-success" title="Reactivate"><i class="bi bi-person-check"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<section id="prescriptionManagementSection" class="d-none" tabindex="0">
  <h2 class="section-title">Prescription Management</h2>

  <!-- Search and Filter -->
  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" id="prescriptionSearch" class="form-control" placeholder="Search by User, ID, or Status" aria-label="Search prescriptions" />
    </div>
    <div class="col-md-3">
      <select id="statusFilter" class="form-select" aria-label="Filter by prescription status">
        <option value="">All Statuses</option>
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>
    <div class="col-md-3 text-end">
      <button id="clearFilters" class="btn btn-secondary">Clear Filters</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle" aria-describedby="prescriptionTableCaption">
      <caption id="prescriptionTableCaption" class="visually-hidden">List of user prescriptions for management</caption>
      <thead class="table-light">
        <tr>
          <th scope="col">Prescription ID</th>
          <th scope="col">User</th>
          <th scope="col">File Name</th>
          <th scope="col">Upload Date</th>
          <th scope="col">Status</th>
          <th scope="col">Pharmacist</th>
          <th scope="col" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="prescriptionTableBody">
        <tr>
          <td>P001</td>
          <td>John Doe</td>
          <td><a href="uploads/prescription_01.pdf" target="_blank" rel="noopener noreferrer">prescription_01.pdf</a></td>
          <td>2025-07-28</td>
          <td><span class="badge bg-warning text-dark">Pending</span></td>
          <td>Not Assigned</td>
          <td class="text-center">
            <button class="btn btn-sm btn-success me-1" title="Approve"><i class="bi bi-check-lg"></i></button>
            <button class="btn btn-sm btn-danger me-1" title="Reject"><i class="bi bi-x-lg"></i></button>
            <button class="btn btn-sm btn-primary me-1" title="Assign Pharmacist"><i class="bi bi-person-plus"></i></button>
            <button class="btn btn-sm btn-info" title="View Logs"><i class="bi bi-journal-text"></i></button>
          </td>
        </tr>
        <tr>
          <td>P002</td>
          <td>Mary Smith</td>
          <td><a href="uploads/prescription_02.pdf" target="_blank" rel="noopener noreferrer">prescription_02.pdf</a></td>
          <td>2025-07-20</td>
          <td><span class="badge bg-success">Approved</span></td>
          <td>Pharmacist A</td>
          <td class="text-center">
            <button class="btn btn-sm btn-primary me-1" title="Assign Pharmacist"><i class="bi bi-person-plus"></i></button>
            <button class="btn btn-sm btn-info" title="View Logs"><i class="bi bi-journal-text"></i></button>
          </td>
        </tr>
        <tr>
          <td>P003</td>
          <td>Mark Johnson</td>
          <td><a href="uploads/prescription_03.pdf" target="_blank" rel="noopener noreferrer">prescription_03.pdf</a></td>
          <td>2025-07-22</td>
          <td><span class="badge bg-danger">Rejected</span></td>
          <td>Pharmacist B</td>
          <td class="text-center">
            <button class="btn btn-sm btn-primary me-1" title="Assign Pharmacist"><i class="bi bi-person-plus"></i></button>
            <button class="btn btn-sm btn-info" title="View Logs"><i class="bi bi-journal-text"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <nav aria-label="Prescription table pagination" class="mt-3">
    <ul class="pagination justify-content-center">
      <li class="page-item disabled"><button class="page-link" tabindex="-1" aria-disabled="true">Previous</button></li>
      <li class="page-item active"><button class="page-link">1</button></li>
      <li class="page-item"><button class="page-link">2</button></li>
      <li class="page-item"><button class="page-link">3</button></li>
      <li class="page-item"><button class="page-link">Next</button></li>
    </ul>
  </nav>
</section>


<!-- Pharmacist Management -->
<section id="pharmacistManagementSection" class="d-none" tabindex="0">
  <h2 class="section-title">Pharmacist Management</h2>
  <button class="btn btn-primary mb-3">Add New Pharmacist</button>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Pharmacist ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Prescriptions Handled</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PH001</td>
          <td>Dr. Sarah Lee</td>
          <td>sarah.lee@example.com</td>
          <td>45</td>
          <td>
            <button class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        <tr>
          <td>PH002</td>
          <td>Dr. Michael Chen</td>
          <td>michael.chen@example.com</td>
          <td>38</td>
          <td>
            <button class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<!-- Product Management -->
<section id="productManagementSection" class="d-none" tabindex="0">
  <h2 class="section-title">Product Management</h2>
  <button class="btn btn-primary mb-3">Add New Product</button>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PR001</td>
          <td>Paracetamol</td>
          <td>Pain Relief</td>
          <td>$5.00</td>
          <td>120</td>
          <td><span class="badge bg-success">Active</span></td>
          <td>
            <button class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
            <button class="btn btn-sm btn-secondary" title="Toggle Stock Status"><i class="bi bi-toggle-on"></i></button>
          </td>
        </tr>
        <tr>
          <td>PR002</td>
          <td>Ibuprofen</td>
          <td>Pain Relief</td>
          <td>$6.50</td>
          <td>0</td>
          <td><span class="badge bg-danger">Out of Stock</span></td>
          <td>
            <button class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
            <button class="btn btn-sm btn-secondary" title="Toggle Stock Status"><i class="bi bi-toggle-off"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<!-- Best-Selling Items Management -->
<section id="bestSellingSection" class="d-none" tabindex="0">
  <h2 class="section-title">Best-Selling Items Management</h2>
  <p>Manually mark or unmark products as "Best-Selling".</p>
  <ul class="list-group mb-3">
    <li class="list-group-item d-flex justify-content-between align-items-center">
      Paracetamol
      <button class="btn btn-sm btn-outline-danger">Unmark</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      Vitamin C
      <button class="btn btn-sm btn-outline-danger">Unmark</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      Aspirin
      <button class="btn btn-sm btn-outline-danger">Unmark</button>
    </li>
  </ul>
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="autoMarkToggle" />
    <label class="form-check-label" for="autoMarkToggle">Enable auto-marking based on sales (future feature)</label>
  </div>
</section>

<!-- CMS / Content Management -->
<section id="cmsSection" class="d-none" tabindex="0">
  <h2 class="section-title">CMS / Content Management</h2>
  <p>Manage static pages and homepage content.</p>
  <button class="btn btn-primary mb-3">Add New Page</button>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Page ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Last Updated</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PG001</td>
          <td>About Us</td>
          <td><span class="badge bg-success">Published</span></td>
          <td>2025-07-20</td>
          <td>
            <button class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        <tr>
          <td>PG002</td>
          <td>Privacy Policy</td>
          <td><span class="badge bg-secondary">Draft</span></td>
          <td>2025-07-15</td>
          <td>
            <button class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

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
      if(targetSection) {
        targetSection.classList.remove('d-none');
        // Optionally focus for accessibility
        targetSection.focus();
      }
    });
  });

  // Optionally: Show dashboard by default
  hideAllSections();
  const defaultSection = document.getElementById('dashboardSection');
  if(defaultSection) {
    defaultSection.classList.remove('d-none');
  }
});

    </script>


</body>

</html>