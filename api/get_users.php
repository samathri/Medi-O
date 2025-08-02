<?php
header('Content-Type: application/json');
require_once '../includes/db.php'; // adjust path as needed

try {
    $stmt = $pdo->query("SELECT id, name, email, phone, address, role, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to fetch users']);
    exit();
}
