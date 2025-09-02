<?php
// order_success.php
session_start();
include('../backend/pages/db.php');
include('../backend/libs/JWT.php');

define('JWT_SECRET', 'grm_shop_secret_key_2024');

function decode_jwt_to_array($token) {
    try {
        $decoded = JWT::decode($token, JWT_SECRET);
        return json_decode(json_encode($decoded), true);
    } catch (Exception $e) {
        return null;
    }
}

$user = null;
if (!empty($_COOKIE['auth_token'])) {
    $user = decode_jwt_to_array($_COOKIE['auth_token']);
}
if (!$user || empty($user['user_id'])) {
    header("Location: account.php");
    exit();
}

$loggedUserId = intval($user['user_id']);
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    header("Location: index.php");
    exit();
}

/* ===================
   1) Fetch order
   =================== */
$orderSql = "
    SELECT
      o.id, o.order_number, o.user_id,
      o.subtotal AS subtotal_amount,
      o.shipping_fee AS shipping_amount,
      o.gst_amount,
      o.grand_total,
      o.order_status,
      o.created_at,
      o.tracking_number
    FROM orders o
    WHERE o.id = ? AND o.user_id = ?
    LIMIT 1
";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("ii", $order_id, $loggedUserId);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    $stmt->close();
    header("Location: index.php");
    exit();
}
$order = $res->fetch_assoc();
$stmt->close();

/* ==========================
   2) Shipping address
   ========================== */
$shipAddr = null;
$addrSql = "SELECT first_name, last_name, address1, address2, city, state, zip_code, mobile
            FROM order_addresses
            WHERE order_id = ? AND type = 'shipping'
            LIMIT 1";
$stmt = $conn->prepare($addrSql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows) {
    $shipAddr = $res->fetch_assoc();
}
$stmt->close();

/* ==========================
   3) Order items
   ========================== */
$items = [];
$itemSql = "SELECT id, name, product_code, variation_id, size, qty, unit_price, total_price
            FROM order_items
            WHERE order_id = ?";
$stmt = $conn->prepare($itemSql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows) {
    $items = $res->fetch_all(MYSQLI_ASSOC);
}
$stmt->close();

/* ==========================
   4) Payment
   ========================== */
$payment = null;
$paySql = "SELECT id, payment_method, payment_status, upi_id, card_brand, card_last4, amount, created_at
           FROM order_payments
           WHERE order_id = ?
           ORDER BY id DESC
           LIMIT 1";
$stmt = $conn->prepare($paySql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows) {
    $payment = $res->fetch_assoc();
}
$stmt->close();

include("header.php");
?>

<div class="container py-80">
    <h3 class="mb-4">Order Placed Successfully!</h3>
    <p>Order Number: <strong><?= htmlspecialchars($order['order_number']) ?></strong></p>
    <p>Date: <?= htmlspecialchars($order['created_at']) ?></p>
    <hr>
    <h5>Shipping Address</h5>
    <?php if ($shipAddr): ?>
        <p>
            <?= htmlspecialchars($shipAddr['first_name'] . ' ' . $shipAddr['last_name']) ?><br>
            <?= htmlspecialchars($shipAddr['address1']) ?><br>
            <?= htmlspecialchars($shipAddr['address2']) ?><br>
            <?= htmlspecialchars($shipAddr['city']) ?>, <?= htmlspecialchars($shipAddr['state']) ?> <?= htmlspecialchars($shipAddr['zip_code']) ?><br>
            Mobile: <?= htmlspecialchars($shipAddr['mobile']) ?>
        </p>
    <?php endif; ?>
    <hr>
    <h5>Payment</h5>
    <p>
        <strong><?= htmlspecialchars(strtoupper($payment['payment_method'] ?? 'N/A')) ?></strong><br>
        Status: <?= htmlspecialchars($payment['payment_status'] ?? 'pending') ?><br>
        <?php if (!empty($payment['upi_id'])): ?>
            UPI ID: <?= htmlspecialchars($payment['upi_id']) ?><br>
        <?php elseif (!empty($payment['card_last4'])): ?>
            Card ending in <?= htmlspecialchars($payment['card_last4']) ?> (<?= htmlspecialchars($payment['card_brand'] ?? '') ?>)
        <?php endif; ?>
    </p>
    <hr>
    <h5>Order Items</h5>
    <?php foreach ($items as $item): ?>
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h6 class="mb-1"><?= htmlspecialchars($item['name']) ?></h6>
                <div class="text-muted small">
                    Code: <?= htmlspecialchars($item['product_code']) ?> | 
                    Var: <?= htmlspecialchars($item['variation_id']) ?> |
                    Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?> |
                    Qty: <?= intval($item['qty']) ?>
                </div>
            </div>
            <div class="text-end">
                <div class="fw-semibold">₹<?= number_format($item['total_price'], 2) ?></div>
                <div class="text-muted small">₹<?= number_format($item['unit_price'], 2) ?> each</div>
            </div>
        </div>
    <?php endforeach; ?>
    <hr>
    <h5>Order Summary</h5>
    <p>
        Subtotal: ₹<?= number_format($order['subtotal_amount'], 2) ?><br>
        Shipping: ₹<?= number_format($order['shipping_amount'], 2) ?><br>
        GST: ₹<?= number_format($order['gst_amount'], 2) ?><br>
        <strong>Grand Total: ₹<?= number_format($order['grand_total'], 2) ?></strong>
    </p>
</div>

<?php include("footer.php"); ?>