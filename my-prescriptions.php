<?php
// Include the database connection
include 'includes/db.php';

// Start the session to access the logged-in user's ID
$userId = $_SESSION['user_id']; // Assuming the user ID is stored in session

// Fetch the user's prescriptions from the "prescriptions_2" table
$sql = "SELECT * FROM `prescriptions_2` WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
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
    $fileName = basename($file['name']);
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    
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
    }
}
?>
