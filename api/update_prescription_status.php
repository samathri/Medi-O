<?php
header('Content-Type: application/json');
require_once '../includes/db.php'; // adjust path as needed

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$id = (int)$data['id'];
$status = $data['status'];
$allowedStatuses = ['Pending', 'Approved', 'Assigned', 'Ready', 'Collected'];

if (!in_array($status, $allowedStatuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid status value']);
    exit;
}

try {
    // Check if prescription exists
    $stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE id = ?");
    $stmt->execute([$id]);
    $prescription = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prescription) {
        http_response_code(404);
        echo json_encode(['error' => 'Prescription not found']);
        exit;
    }

    // Update status
    $stmt = $pdo->prepare("UPDATE prescriptions SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);

    // Optionally, return the updated prescription
    $stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE id = ?");
    $stmt->execute([$id]);
    $updatedPrescription = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'message' => 'Prescription status updated',
        'prescription' => $updatedPrescription
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update status']);
}
?>
