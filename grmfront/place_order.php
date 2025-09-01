<?php
session_start();
include('../backend/pages/db.php');
include_once 'razorpay_config.php';

// Throw exceptions on MySQL errors for easier handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && !empty($_SESSION['cart'])
    && isset($_POST['csrf_token'])
    && isset($_SESSION['csrf_token'])
    && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {

    // 1. Get form data (trim; rely on prepared statements for DB safety)
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $billing_address = isset($_POST['billing_address']) && $_POST['billing_address'] !== ''
        ? trim($_POST['billing_address'])
        : $shipping_address;
    $courier_name = trim($_POST['courier'] ?? '');
    $payment_mode = trim($_POST['payment_mode'] ?? 'Cash on Delivery');
    $guest_email = trim($_POST['email'] ?? '');
    if ($guest_email === '') {
        $guest_email = 'guest+' . uniqid('', true) . '@example.local';
    }

    if ($name === '' || $mobile === '' || $shipping_address === '' || $courier_name === '') {
        http_response_code(400);
        echo 'Missing required fields.';
        exit();
    }

    try {
        $conn->begin_transaction();

        // 2. Determine customer ID
        if (isset($_SESSION['cust_id']) && (int)$_SESSION['cust_id'] > 0) {
            $cust_id = (int)$_SESSION['cust_id'];
        } else {
            // Guest checkout - create a new customer record (minimal fields)
            $password_hash = password_hash('guest123', PASSWORD_DEFAULT);
            $sql_cust = "INSERT INTO customers (customer_name, email, password) VALUES (?, ?, ?)";
            $stmt_cust = $conn->prepare($sql_cust);
            $stmt_cust->bind_param("sss", $name, $guest_email, $password_hash);
            $stmt_cust->execute();
            $cust_id = $stmt_cust->insert_id;
            $_SESSION['cust_id'] = $cust_id;
        }

        // 3. Insert into orders table
        $sql_order = "INSERT INTO orders 
            (cust_id, customer_name, mobile_num, courier_name, shipping_address, payment_mode) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("isssss", $cust_id, $name, $mobile, $courier_name, $shipping_address, $payment_mode);
        $stmt_order->execute();
        $order_id = $stmt_order->insert_id; 
    
        // 4. Generate tracking number
        $tracking_num = "TRK" . strtoupper(substr(md5(uniqid('', true)), 0, 8));
        $stmt_upd = $conn->prepare("UPDATE orders SET tracking_num = ? WHERE order_id = ?");
        $stmt_upd->bind_param("si", $tracking_num, $order_id);
        $stmt_upd->execute();
        if ($stmt_upd->affected_rows === 0) {
            $stmt_upd2 = $conn->prepare("UPDATE orders SET tracking_num = ? WHERE id = ?");
            $stmt_upd2->bind_param("si", $tracking_num, $order_id);
            $stmt_upd2->execute();
        }

        // 5. Insert order items
        $sql_item = "INSERT INTO order_items (order_id, product_name, variation_id, image_url, size, quantity, price) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);

        foreach ($_SESSION['cart'] as $item) {
            $product_name = (string)$item['name'];
            $variation_id_int = null;
            if (isset($item['variation_id']) && $item['variation_id'] !== '' && is_numeric($item['variation_id'])) {
                $variation_id_int = (int)$item['variation_id'];
            }
            $image_url = isset($item['image']) ? (string)$item['image'] : '';
            $size = isset($item['size']) ? (string)$item['size'] : '';
            $qty = (int)$item['qty'];
            $price = (float)$item['price'];

            // Correct types: i (order_id), s (product), i (variation_id), s (image), s (size), i (qty), d (price)
            $stmt_item->bind_param("isissid", $order_id, $product_name, $variation_id_int, $image_url, $size, $qty, $price);
            $stmt_item->execute();
        }

        // 6. If payment through Razorpay, verify signature then mark as paid
        $rzpPaymentId = trim($_POST['razorpay_payment_id'] ?? '');
        $rzpOrderId = trim($_POST['razorpay_order_id'] ?? '');
        $rzpSignature = trim($_POST['razorpay_signature'] ?? '');

        $isOnline = in_array(strtolower($payment_mode), ['upi','debit card','credit card']);
        $isPaid = 0;
        if ($isOnline && $rzpPaymentId && $rzpOrderId && $rzpSignature) {
            $generated = hash_hmac('sha256', $rzpOrderId . '|' . $rzpPaymentId, RAZORPAY_KEY_SECRET);
            if (hash_equals($generated, $rzpSignature)) {
                $isPaid = 1;
            }
        }

        // store payment status if field exists
        try {
            $stmt_pay = $conn->prepare("UPDATE orders SET payment_status = ? WHERE order_id = ?");
            $stmt_pay->bind_param('ii', $isPaid, $order_id);
            $stmt_pay->execute();
        } catch (mysqli_sql_exception $e) {
            // ignore if column does not exist
        }

        // 7. Commit and clear cart/CSRF
        $conn->commit();
        unset($_SESSION['cart']);
        unset($_SESSION['csrf_token']);

        // 8. Redirect to success page
        header("Location: add_to_cart.php?order_id=$order_id&tracking=$tracking_num");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($conn->errno) {
            $conn->rollback();
        }
        error_log('Order placement failed: ' . $e->getMessage());
        http_response_code(500);
        echo "Sorry, we couldn't place your order right now.";
        exit();
    }

} else {
    http_response_code(400);
    echo "No items in cart or invalid request.";
}
?>
