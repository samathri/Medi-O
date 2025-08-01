<?php
$host = "localhost";
$user = "root";
$pass = ""; // update if you set a password
$db = "medi-o"; // ✅ your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
