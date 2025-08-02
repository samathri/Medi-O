<?php
include 'includes/db.php';

// Handle Approve Prescription
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    // Update the status of the prescription to Approved
    $conn->query("UPDATE prescriptions SET status='Approved' WHERE id=$id");
    header("Location: admin.php?section=prescriptionManagement"); // Redirect to prescription management section
    exit;
}

// Handle Reject Prescription
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    // Update the status of the prescription to Rejected
    $conn->query("UPDATE prescriptions SET status='Rejected' WHERE id=$id");
    header("Location: admin.php?section=prescriptionManagement"); // Redirect to prescription management section
    exit;
}

// Handle Assign Pharmacist
if (isset($_GET['assignPharmacist'])) {
    $id = $_GET['assignPharmacist'];
    $pharmacistId = $_GET['pharmacistId']; // Assuming pharmacist ID is passed in the URL
    // Update the pharmacist for the prescription
    $conn->query("UPDATE prescriptions SET pharmacist_id=$pharmacistId WHERE id=$id");
    header("Location: admin.php?section=prescriptionManagement"); // Redirect to prescription management section
    exit;
}

// Handle Search and Filter
$searchQuery = '';
$statusFilter = '';
$searchSql = '';
$statusSql = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchSql = " AND (user_id IN (SELECT id FROM users WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%') 
                    OR id LIKE '%$searchQuery%' OR status LIKE '%$searchQuery%')";
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $statusFilter = $_GET['status'];
    $statusSql = " AND status = '$statusFilter'";
}

// Fetch Prescriptions with Optional Search and Filter
$sql = "SELECT * FROM prescriptions WHERE 1" . $searchSql . $statusSql . " ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    // If query fails, show the error
    die("Query failed: " . $conn->error);
}

// Fetch Pharmacists for Assigning
$pharmacistsSql = "SELECT id, name FROM users WHERE role = 'pharmacist'"; // Assuming 'role' exists
$pharmacistsResult = $conn->query($pharmacistsSql);

if (!$pharmacistsResult) {
    // If query fails, show the error
    die("Pharmacists query failed: " . $conn->error);
}



// Function to get pharmacist name by ID
function getPharmacistName($pharmacistId) {
    global $conn;
    $sql = "SELECT name FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $pharmacistId);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }
    return 'Not Assigned';
}
?>


