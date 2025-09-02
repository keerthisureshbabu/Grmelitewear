<?php
// order_place.php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../backend/pages/db.php'; // your $conn mysqli

try {
    if (empty($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }
    if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        throw new Exception("Cart is empty");
    }
    if (empty($_SESSION['shipping_address']) || empty($_SESSION['payment_method'])) {
        throw new Exception("Missing checkout data");
    }

    $user_id = (int) $_SESSION['user_id'];
    $cart = $_SESSION['cart'];
    $address = $_SESSION['shipping_address'];
    $payment = $_SESSION['payment_method'];

    // Calculate totals
    $subtotal = 0.0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }
    // Shipping fee logic (Tamil Nadu: 70, others: 120, free above 1000)
    $shipping_fee = ($subtotal >= 1000) ? 0.0 : (strtolower($address['state']) === 'tamil nadu' ? 70.0 : 120.0);
    $gst_amount   = round(($subtotal + $shipping_fee) * 0.18, 2);
    $grand_total  = $subtotal + $shipping_fee + $gst_amount;

    $conn->begin_transaction();

    /* 1) Insert order */
    $order_number = 'ORD' . time() . rand(100, 999);
    $status = 'pending';

    $orderSql = "
        INSERT INTO orders 
            (order_number, user_id, order_status, subtotal, shipping_fee, gst_amount, grand_total, created_at) 
        VALUES (?,?,?,?,?,?,?,NOW())
    ";
    $stmt = $conn->prepare($orderSql);
    if (!$stmt) throw new Exception("Prepare order failed: " . $conn->error);

    $stmt->bind_param(
        "sissddd",
        $order_number,
        $user_id,
        $status,
        $subtotal,
        $shipping_fee,
        $gst_amount,
        $grand_total
    );
    if (!$stmt->execute()) throw new Exception("Execute order insert failed: " . $stmt->error);
    $order_id = (int) $stmt->insert_id;
    $stmt->close();

    /* 2) Insert shipping address */
    $addrSql = "
        INSERT INTO order_addresses
          (order_id, type, first_name, last_name, address1, address2, city, state, zip_code, mobile)
        VALUES (?, 'shipping', ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($addrSql);
    if (!$stmt) throw new Exception("Prepare addresses failed: " . $conn->error);

    $stmt->bind_param(
        "issssssss",
        $order_id,
        $address['first_name'],
        $address['last_name'],
        $address['address1'],
        $address['address2'],
        $address['city'],
        $address['state'],
        $address['zip_code'],
        $address['mobile']
    );
    if (!$stmt->execute()) throw new Exception("Execute address insert failed: " . $stmt->error);
    $stmt->close();

    /* 3) Insert order_items */
    $itemSql = "
        INSERT INTO order_items
          (order_id, product_code, variation_id, name, size, qty, unit_price, total_price)
        VALUES (?,?,?,?,?,?,?,?)
    ";
    $itemStmt = $conn->prepare($itemSql);
    if (!$itemStmt) throw new Exception("Prepare items failed: " . $conn->error);

    foreach ($cart as $item) {
        $itemStmt->bind_param(
            "isssiddd",
            $order_id,
            $item['product_code'],
            $item['variation_id'],
            $item['product_name'],
            $item['attribute'],
            $item['qty'],
            $item['price'],
            $item['price'] * $item['qty']
        );
        if (!$itemStmt->execute()) throw new Exception("Execute item insert failed: " . $itemStmt->error);
    }
    $itemStmt->close();

    /* 4) Insert payment row */
    $paySql = "
        INSERT INTO order_payments
          (order_id, payment_method, payment_status, upi_id, card_last4, card_brand, amount)
        VALUES (?,?,?,?,?,?,?)
    ";
    $payStmt = $conn->prepare($paySql);
    if (!$payStmt) throw new Exception("Prepare payment failed: " . $conn->error);

    $payment_status = 'pending';
    $upi_id   = ($payment['method'] === 'upi') ? $payment['upi_id'] : null;
    $last4    = $payment['card_last4'] ?? null;
    $brand    = $payment['card_brand'] ?? null;

    $payStmt->bind_param(
        "isssssd",
        $order_id,
        $payment['method'],
        $payment_status,
        $upi_id,
        $last4,
        $brand,
        $grand_total
    );
    if (!$payStmt->execute()) throw new Exception("Execute payment insert failed: " . $payStmt->error);
    $payStmt->close();

    $conn->commit();

    // Clear cart and checkout session data
    unset($_SESSION['cart'], $_SESSION['shipping_address'], $_SESSION['payment_method']);

    // Redirect to order success
    header("Location: order_success.php?order_id={$order_id}");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "<h3>Order Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
    exit();
}