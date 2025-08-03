<?php
include 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-danger'>User not logged in.</p>";
    exit;
}

$userId = $_SESSION['user_id'];

// ========== FETCH PRESCRIPTIONS ==========
$sql = "SELECT * FROM `prescriptions_2` WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result(); // Result available for later display

// ========== HANDLE PRESCRIPTION UPLOAD ==========
if (isset($_POST['uploadPrescription'])) {
    $file = $_FILES['prescriptionFile'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $fileName = basename($file['name']);
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

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
    }
}
?>
