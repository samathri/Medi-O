<?php
session_start();
?>

<?php include 'upload-prescription.php'; ?>

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

    



    <main class="container my-5 c-profile">


<!-- Upload Prescription Form -->
<section aria-labelledby="upload-prescription-section-title" class="form-section">
    <h2 id="upload-prescription-section-title" class="section-title">Upload Prescription</h2>
  
    <!-- Prescription upload form -->
    <form action="upload-prescription.php" method="POST" enctype="multipart/form-data">
        <!-- Prescription file upload -->
        <div class="mb-3">
            <label for="prescription-file" class="form-label">Prescription File</label>
            <input type="file" id="prescription-file" name="prescription-file" accept="image/*,application/pdf" class="form-control" required />
        </div>

        <!-- Hidden field for user ID (or you can get it from the session) -->
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>" />

        <!-- Submit button -->
        <button type="submit" name="upload_prescription" class="btn btn-primary">Upload Prescription</button>
    </form>
</section>



<!-- My Prescriptions Section -->
<section aria-labelledby="prescriptions-section-title" class="form-section">
  <h2 id="prescriptions-section-title" class="section-title">My Prescriptions</h2>
  
  <!-- Table for displaying prescriptions -->
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Prescription ID</th>
          <th>Prescription File</th>
          <th>User Name</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <!-- Example data rows (these will be dynamically generated via PHP) -->
        <tr>
          <td>1</td>
          <td>
            <a href="uploads/prescriptions/example-prescription.jpg" target="_blank" download>
              View Prescription
            </a>
          </td>
          <td>John Doe</td>
          <td>Pending</td>
        </tr>
        <tr>
          <td>2</td>
          <td>
            <a href="uploads/prescriptions/example-prescription2.jpg" target="_blank" download>
              View Prescription
            </a>
          </td>
          <td>Jane Smith</td>
          <td>Approved</td>
        </tr>
        <!-- More rows will be added dynamically from the backend -->
      </tbody>
    </table>
  </div>
</section>





        <!-- Account Section -->
        <section aria-labelledby="account-section-title" class="form-section">
            <h2 id="account-section-title" class="section-title">Account Details</h2>
            <form id="profileForm" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="John Doe" required />
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="john@example.com" required />
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" placeholder="+94 77 123 4567" required />
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="123 Street, City, Country"
                            required />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
            </form>

            <!-- Change Password -->
            <hr class="my-4" />
            <h3 class="section-title">Change Password</h3>
            <form id="passwordForm" novalidate>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="oldPassword" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="oldPassword" required />
                    </div>
                    <div class="col-md-4">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required />
                    </div>
                    <div class="col-md-4">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary mt-3">Update Password</button>
            </form>
        </section>

    </main>







<!-- Footer -->
<?php include("components/footer.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const steps = document.querySelectorAll('.progress-step');

            // Suppose currentStatus can be 'pending', 'order-processing', or 'ready-to-collect'
            // Map each status to index 0,1,2
            const currentStatus = 'order-processing'; // Example

            const statusMap = {
                'pending': 0,
                'order-processing': 1,
                'ready-to-collect': 2
            };

            const currentIndex = statusMap[currentStatus];

            steps.forEach((step, index) => {
                if (index <= currentIndex) {
                    step.classList.add('completed');
                } else {
                    step.classList.remove('completed');
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
  // Example to show QR code and prescription details once the prescription is approved
  const prescriptions = document.querySelectorAll('.prescription-item');

  prescriptions.forEach(item => {
    const status = item.querySelector('.status-pending'); // Change this to get actual status dynamically
    const qrCode = item.querySelector('.qr-code');
    const details = item.querySelector('.prescription-details');
    const approveButton = item.querySelector('.approve-button');

    if (status && status.textContent.trim() === 'Approved') {
      qrCode.style.display = 'block';
      details.style.display = 'block';
      approveButton.style.display = 'none';  // Hide approve button if already approved
    } else if (status && status.textContent.trim() === 'Reviewed') {
      approveButton.style.display = 'block';  // Show approve button if prescription is reviewed
    }
  });
});


    </script>


</body>

</html>