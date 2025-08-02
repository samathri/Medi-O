<?php
// Include database connection
include 'includes/db.php';

// Assuming the user is logged in and we have the user ID in session (replace this with actual session logic)
session_start();
$user_id = $_SESSION['user_id']; // You may need to adjust this based on your session logic

// Fetch prescriptions uploaded by the logged-in user
$sql = "SELECT * FROM prescriptions WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- My Prescriptions Section -->
<section aria-labelledby="prescriptions-section-title" class="form-section">
  <h2 id="prescriptions-section-title" class="section-title">My Prescriptions</h2>
  
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Prescription ID</th>
          <th>Prescription Image</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td>
            <!-- Link to download prescription file -->
            <a href="<?= htmlspecialchars($row['file_path']) ?>" download>Download Prescription</a>
          </td>
          <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
