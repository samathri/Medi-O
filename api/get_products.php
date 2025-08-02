<?php
// get_products.php

$host = 'localhost';
$db = 'medi_o_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

header('Content-Type: application/json');

// Handle count mode
if (isset($_GET['mode']) && $_GET['mode'] === 'count') {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
        $count = $stmt->fetch();
        echo json_encode(['total' => (int)$count['total']]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch count']);
    }
    exit;
}

// Fetch all products
try {
    $stmt = $pdo->query("
        SELECT 
            id, 
            name, 
            category, 
            price, 
            stock, 
            description, 
            image_path, 
            best_selling,
            DATE_FORMAT(created_at, '%Y-%m-%d') as created_at 
        FROM products 
        ORDER BY created_at DESC
    ");
    
    $products = $stmt->fetchAll();

    // Convert best_selling to boolean and prepend base URL to image_path if needed
    $baseImageUrl = 'http://localhost/medi-o/'; // Adjust your base URL here
    foreach ($products as &$product) {
        $product['best_selling'] = (bool)$product['best_selling'];
        
        // Prepend base URL if image_path is relative and not empty
        if (!empty($product['image_path']) && strpos($product['image_path'], 'http') !== 0) {
            $product['image_path'] = $baseImageUrl . ltrim($product['image_path'], '/');
        }
    }

    echo json_encode($products);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch products']);
}
