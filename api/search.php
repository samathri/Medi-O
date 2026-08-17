<?php
/** Live search suggestions (public). */
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
$q = trim($_GET['q'] ?? '');
if (mb_strlen($q) < 2) {
    echo '[]';
    exit;
}

$rows = q_all('SELECT p.id, p.name, p.price, p.image_path, c.name AS category
               FROM products p LEFT JOIN categories c ON c.id = p.category_id
               WHERE p.is_active = 1 AND p.name LIKE ? ORDER BY p.rating DESC LIMIT 8', ["%$q%"]);

echo json_encode(array_map(fn($p) => [
    'id' => (int)$p['id'],
    'name' => e($p['name']),
    'category' => e($p['category'] ?? ''),
    'price' => money((float)$p['price']),
    'image' => product_image($p['image_path']),
], $rows));
