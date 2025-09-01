<?php
include("db.php");

header('Content-Type: application/json');

$category = $_GET['category'] ?? '';
$product_code = $_GET['product_code'] ?? '';

if (!$category || !$product_code) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$productTables = [
    'kids' => 'kids_products',
    'women' => 'womens_products',
    'toy' => 'toys_products',
    'accessories' => 'accessories_products'
];

if (!isset($productTables[$category])) {
    echo json_encode(['success' => false, 'message' => 'Invalid category']);
    exit;
}

$table = $productTables[$category];
$escaped_product_code = $conn->real_escape_string($product_code);

$query = "SELECT id, attribute, inventory_count, variation_code FROM $table WHERE product_code = '$escaped_product_code' ORDER BY attribute";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}

$variations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $variations[] = [
        'id' => $row['id'],
        'attribute' => $row['attribute'],
        'inventory_count' => intval($row['inventory_count']),
        'variation_code' => $row['variation_code']
    ];
}

echo json_encode([
    'success' => true,
    'variations' => $variations
]);
?>
