<?php
session_start();

if (isset($_POST['id']) && isset($_POST['qty'])) {
    $id = $_POST['id'];
    $qty = max(1, intval($_POST['qty']));
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] = $qty;
        $price = floatval($_SESSION['cart'][$id]['price']);
        $subtotal = $qty * $price;

        echo json_encode([
            'success' => true,
            'subtotal' => $subtotal
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
