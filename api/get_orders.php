<?php
header('Content-Type: application/json');

// Use require_once for including DB connection file
require_once '../includes/db.php';  // Adjust path as needed

try {
    // Prepare the query to get orders and user info
    $stmt = $pdo->prepare("
        SELECT 
            o.id, 
            o.total_amount, 
            o.status, 
            o.created_at, 
            u.name AS user_name, 
            u.email AS user_email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
    ");

    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output JSON encoded orders array
    echo json_encode(['success' => true, 'orders' => $orders]);

} catch (Exception $e) {
    // Send 500 Internal Server Error
    http_response_code(500);

    // Output error message (optionally include $e->getMessage() for debugging)
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch orders',
        // 'debug' => $e->getMessage()  // uncomment for development only
    ]);

    exit();  // stop script execution after error
}
