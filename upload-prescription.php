<?php
include 'includes/db.php';  // Include the database connection

// Initialize success message variable
$success_message = '';

// Handle prescription file upload
if (isset($_POST['upload_prescription'])) {
    // Collect the data from the form
    $user_id = $_POST['user_id'];  // Make sure this is passed securely (e.g., from session ideally)

    // Define the upload directory
    $upload_dir = "uploads/prescriptions/";

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true);
    }

    // Check for uploaded file
    if (isset($_FILES['prescription-file']) && $_FILES['prescription-file']['error'] === 0) {
        $file = $_FILES['prescription-file'];
        $file_name = uniqid() . '-' . basename($file['name']);
        $file_target = $upload_dir . $file_name;

        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_target)) {
            // Default values for the new columns
            $created_at = date('Y-m-d H:i:s');
            $is_ready = 0;
            $is_picked_up = 0;

            // Insert into prescriptions_2 table
            $stmt = $conn->prepare("INSERT INTO prescriptions_2 (user_id, file_path, created_at, is_ready, is_picked_up) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issii", $user_id, $file_target, $created_at, $is_ready, $is_picked_up);

            if ($stmt->execute()) {
                header("Location: profile.php?upload=success");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Database error: Failed to save prescription.</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Failed to move uploaded file.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No file uploaded or upload error occurred.</div>";
    }
}
?>
