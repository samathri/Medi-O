<?php
<<<<<<< HEAD
include 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-danger'>User not logged in.</p>";
    exit;
}

$userId = $_SESSION['user_id'];

// ========== FETCH PRESCRIPTIONS ==========
=======
// Include the database connection
include 'includes/db.php';

// Start the session to access the logged-in user's ID
$userId = $_SESSION['user_id']; // Assuming the user ID is stored in session

// Fetch the user's prescriptions from the "prescriptions_2" table
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
$sql = "SELECT * FROM `prescriptions_2` WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
<<<<<<< HEAD
$result = $stmt->get_result(); // Result available for later display

// ========== HANDLE PRESCRIPTION UPLOAD ==========
if (isset($_POST['uploadPrescription'])) {
    $file = $_FILES['prescriptionFile'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

=======
$result = $stmt->get_result(); // Get the result of the query
?>


<?php
// Handle Prescription Upload (Optional - if you also want to allow prescription upload in this file)
if (isset($_POST['uploadPrescription'])) {
    // Get the file details
    $file = $_FILES['prescriptionFile'];
    
    // Define allowed file types and max size
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    // Extract file information
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
    $fileName = basename($file['name']);
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
<<<<<<< HEAD

    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        echo "<div class='alert alert-danger'>Only JPG, JPEG, PNG, and PDF files are allowed.</div>";
        exit;
    }

    // Validate file size
    if ($fileSize > $maxFileSize) {
        echo "<div class='alert alert-danger'>The file is too large. Maximum size is 5MB.</div>";
        exit;
    }

    $uniqueFileName = uniqid() . '-' . $fileName;
    $filePath = 'uploads/prescriptions/' . $uniqueFileName;

    if (move_uploaded_file($fileTmpName, $filePath)) {
        $status = 'Pending';

        $insertSql = "INSERT INTO `prescriptions_2` (user_id, file_path, status) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iss", $userId, $uniqueFileName, $status);

        if ($insertStmt->execute()) {
            echo "<div class='alert alert-success'>Prescription uploaded successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Database error. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error uploading file. Please try again.</div>";
=======
    
    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        echo "Only JPG, JPEG, and PNG files are allowed.";
        exit;
    }
    
    // Validate file size
    if ($fileSize > $maxFileSize) {
        echo "The file is too large. Maximum size is 5MB.";
        exit;
    }
    
    // Generate a unique name for the file to avoid overwriting
    $uniqueFileName = uniqid() . '-' . $fileName;
    $filePath = 'uploads/prescriptions/' . $uniqueFileName;
    
    // Move the file to the destination folder
    if (move_uploaded_file($fileTmpName, $filePath)) {
        // Insert the file path into the "prescriptions_2" table
        $status = 'Pending'; // Default status is 'Pending'
        $sql = "INSERT INTO `prescriptions_2` (user_id, file_path, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $uniqueFileName, $status);
        
        if ($stmt->execute()) {
            echo "Prescription uploaded successfully!";
        } else {
            echo "Error uploading prescription. Please try again.";
        }
    } else {
        echo "There was an error uploading the file. Please try again.";
>>>>>>> c1b341e3aa8095897315d88f1f94e4fc298b70ef
    }
}
?>
