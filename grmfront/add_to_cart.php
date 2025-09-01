<?php
// add_to_cart.php
declare(strict_types=1);

session_start();
include('../backend/pages/db.php'); // must provide $conn (mysqli)

// Helper to show an error and stop
function fail(string $msg) {
    echo '<div style="padding:30px;font-family:Arial;">';
    echo '<h3>Error</h3>';
    echo '<p>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p><a href="javascript:history.back()">Go back</a></p>';
    echo '</div>';
    exit;
}

// Allowed product tables by category key
$productTables = [
    'kids'        => 'kids_products',
    'women'       => 'womens_products',
    'toy'         => 'toys_products',
    'accessories' => 'accessories_products',
];

// Collect POST safely
$category     = trim((string)($_POST['category'] ?? ''));
$product_code = trim((string)($_POST['product_code'] ?? ''));
$variation_id = 0;

// accept either variation_id or selected_variation_id (support both)
if (isset($_POST['variation_id'])) {
    $variation_id = intval($_POST['variation_id']);
} elseif (isset($_POST['selected_variation_id'])) {
    $variation_id = intval($_POST['selected_variation_id']);
}

// qty
$qty_input = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
$qty = max(1, $qty_input);

// Basic validation
if ($category === '' || $product_code === '' || $variation_id <= 0) {
    fail('Invalid request. Missing product information.');
}

if (!array_key_exists($category, $productTables)) {
    fail('Invalid category.');
}

$table = $productTables[$category];

// Prepare SELECT for the variation (use prepared statement)
$sql = "SELECT * FROM `$table` WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    fail('Database error (prepare failed).');
}
$stmt->bind_param('i', $variation_id);
if (!$stmt->execute()) {
    $stmt->close();
    fail('Database error (execute failed).');
}
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    $stmt->close();
    fail('Product/variation not found.');
}
$product = $res->fetch_assoc();
$stmt->close();

// Normalize stock field (support inventory_count or stock)
$stock = 0;
if (isset($product['inventory_count'])) {
    $stock = (int)$product['inventory_count'];
} elseif (isset($product['stock'])) {
    $stock = (int)$product['stock'];
}

// If stock is zero or negative — block add
if ($stock <= 0) {
    fail('Sorry, this item is out of stock.');
}

// Cap qty to stock
if ($qty > $stock) $qty = $stock;

// Prepare session cart
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Use variation id as key
$key = (string)$variation_id;

// If already in cart, increment but cap
if (isset($_SESSION['cart'][$key])) {
    $existingQty = intval($_SESSION['cart'][$key]['qty'] ?? 0);
    $newQty = $existingQty + $qty;
    if ($newQty > $stock) $newQty = $stock;
    $_SESSION['cart'][$key]['qty'] = $newQty;
} else {
    $image = $product['image1'] ?? ($product['image'] ?? '');
    $price = isset($product['actual_price']) ? (float)$product['actual_price'] : ((isset($product['price']) ? (float)$product['price'] : 0.0));
    $name  = $product['product_name'] ?? ($product['name'] ?? 'Product');

    $_SESSION['cart'][$key] = [
        'id'           => (int)$product['id'],
        'product_code' => (string)($product['product_code'] ?? $product_code),
        'product_name' => (string)$name,
        'attribute'    => (string)($product['attribute'] ?? ''),
        'price'        => $price,
        'image'        => (string)$image,
        'qty'          => $qty,
        'category'     => (string)$category,
        'stock'        => $stock,
    ];
}

// set flash (optional)
$_SESSION['flash_message'] = 'Item added to cart.';

// redirect to cart
header('Location: cart.php');
exit;
