<?php
include 'includes/db.php';

// Handle Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Delete the user from the database
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: admin.php?section=userManagement"); // Redirect to user management section
    exit;
}

// Handle User Search
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchSql = " WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
} else {
    $searchSql = '';
}

// Fetch Users (with optional search filter)
$sql = "SELECT * FROM users" . $searchSql . " ORDER BY id DESC";
$result = $conn->query($sql);
?>

