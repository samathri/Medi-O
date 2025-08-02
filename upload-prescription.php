<?php
include 'includes/db.php';  // Make sure to include the database connection

// Initialize success message variable
$success_message = '';

// Handle prescription file upload
if (isset($_POST['upload_prescription'])) {
    // Collect the data from the form
    $user_id = $_POST['user_id'];  // Assuming the user ID is passed from the form or session

    // Define the upload directory for the prescription file
    $upload_dir = "uploads/prescriptions/";

    // Check if the directory exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true);  // Create directory if it doesn't exist
    }

    // Check if a file is uploaded
    if (isset($_FILES['prescription-file']) && $_FILES['prescription-file']['error'] == 0) {
        $file = $_FILES['prescription-file'];
        $file_name = uniqid() . '-' . basename($file['name']);
        $file_target = $upload_dir . $file_name;  // Full path for the uploaded file

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $file_target)) {
            // Set the initial status of the prescription
            $status = 'Pending';  // The prescription is initially "Pending"

            // Prepare the SQL query to insert the prescription into the new table (prescriptions_2)
            $stmt = $conn->prepare("INSERT INTO prescriptions_2 (user_id, file_path, status) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $file_target, $status);

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                // Set the success message
                $success_message = "Prescription uploaded successfully!";
                
                // Redirect to profile.php after successful upload
                header("Location: profile.php?upload=success");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Error uploading prescription. Please try again.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Failed to move the uploaded file. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No file uploaded or there was an error uploading the file.</div>";
    }
}
?>

