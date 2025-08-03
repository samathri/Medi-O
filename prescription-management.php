<?php
include 'includes/db.php'; // Database connection

// Fetch Prescription and User Details
$sql = "
    SELECT p.id AS prescription_id, p.file_path, p.is_ready, 
           u.name AS customer_name, u.phone AS customer_phone, 
           u.address AS customer_address, u.email AS customer_email 
    FROM `prescriptions_2` p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error in fetching prescriptions: " . $conn->error;
}
?>
