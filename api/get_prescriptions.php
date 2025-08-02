<?php
header('Content-Type: application/json');
require_once '../includes/db.php';  // adjust the path as needed

try {
    $stmt = $pdo->prepare("
        SELECT 
            p.id, 
            p.file_path, 
            p.token, 
            p.qr_path, 
            p.status, 
            p.created_at,
            u.name AS user_name, 
            u.email AS user_email,
            ph.name AS pharmacist_name
        FROM prescriptions p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN users ph ON p.pharmacist_id = ph.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'prescriptions' => $prescriptions]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch prescriptions',
        // 'debug' => $e->getMessage() // uncomment for debugging in dev
    ]);
    exit();
}
