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

    


  <div class="pharmacist-dashboard">

    <h1 class="pharmacist-dashboard__title">Pharmacist Dashboard</h1>

    <!-- Assigned Prescriptions Table -->
    <section aria-label="Assigned Prescriptions">
      <h2 class="pharmacist-dashboard__section-title">Assigned Prescriptions</h2>
      <div class="pharmacist-dashboard__table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Patient Name</th>
              <th>Upload Date</th>
              <th>Prescription File</th>
              <th>Status</th>
              <th>QR Code</th>
              <th class="text-center">Update Status</th>
            </tr>
          </thead>
          <tbody id="prescriptionsTableBody">
            <!-- Example row -->
            <tr>
              <td>John Doe</td>
              <td>2025-07-28</td>
              <td>
                <a href="prescription_01.pdf" target="_blank" rel="noopener"
                  aria-label="View prescription file for John Doe" class="pharmacist-dashboard__link">
                  prescription_01.pdf <i class="bi bi-box-arrow-up-right"></i>
                </a>
              </td>
              <td><span class="pharmacist-dashboard__badge pharmacist-dashboard__badge--assigned">Assigned</span></td>
              <td>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=prescription_01"
                  alt="QR Code for prescription 01" class="pharmacist-dashboard__qr-code"
                  title="Click to enlarge QR Code" onclick="showQRModal('prescription_01')" />
              </td>
              <td class="text-center">
                <div class="btn-group" role="group" aria-label="Update prescription status buttons">
                  <button class="btn btn-warning pharmacist-dashboard__btn-sm" onclick="updateStatus(this, 'Packing')"
                    title="Start Packing">Start Packing</button>
                  <button class="btn btn-success pharmacist-dashboard__btn-sm" onclick="updateStatus(this, 'Ready')"
                    title="Mark as Ready">Complete & Ready</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>Mary Smith</td>
              <td>2025-07-20</td>
              <td>
                <a href="prescription_02.pdf" target="_blank" rel="noopener"
                  aria-label="View prescription file for Mary Smith" class="pharmacist-dashboard__link">
                  prescription_02.pdf <i class="bi bi-box-arrow-up-right"></i>
                </a>
              </td>
              <td><span class="pharmacist-dashboard__badge pharmacist-dashboard__badge--pending"
                  style="color:#212529;">Packing</span></td>
              <td>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=prescription_02"
                  alt="QR Code for prescription 02" class="pharmacist-dashboard__qr-code"
                  title="Click to enlarge QR Code" onclick="showQRModal('prescription_02')" />
              </td>
              <td class="text-center">
                <div class="btn-group" role="group" aria-label="Update prescription status buttons">
                  <button class="btn btn-warning pharmacist-dashboard__btn-sm" disabled title="Already packing">Start
                    Packing</button>
                  <button class="btn btn-success pharmacist-dashboard__btn-sm" onclick="updateStatus(this, 'Ready')"
                    title="Mark as Ready">Complete & Ready</button>
                </div>
              </td>
            </tr>
            <!-- More rows dynamically added here -->
          </tbody>
        </table>
      </div>
    </section>

    <!-- Activity History -->
    <section aria-label="Activity History" class="mt-5">
      <h2 class="pharmacist-dashboard__section-title">Activity History</h2>
      <div class="pharmacist-dashboard__table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Date/Time</th>
              <th>Prescription ID</th>
              <th>Previous Status</th>
              <th>Updated Status</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody id="activityHistoryBody">
            <!-- Example logs -->
            <tr>
              <td>2025-07-28 15:30</td>
              <td>P001</td>
              <td>Assigned</td>
              <td>Packing</td>
              <td>Started packing</td>
            </tr>
            <tr>
              <td>2025-07-29 10:15</td>
              <td>P002</td>
              <td>Packing</td>
              <td>Ready</td>
              <td>Marked as ready for pickup</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qrModalLabel">Prescription QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img id="qrModalImg" src="" alt="QR Code Enlarged" style="max-width: 100%; height: auto;" />
          </div>
        </div>
      </div>
    </div>

  </div>




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