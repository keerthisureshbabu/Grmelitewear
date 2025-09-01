<?php 
// order_detail.php
include 'db.php';

// validate order_id
if (!isset($_GET['order_id'])) {
    die("No order_id given");
}
$order_id = intval($_GET['order_id']);
if ($order_id <= 0) {
    die("Invalid order_id");
}

// fetch order with customer name
$order_sql = "
SELECT 
    o.id, 
    o.order_number, 
    o.user_id, 
    c.name AS user_name,
    o.subtotal_amount, 
    o.shipping_amount, 
    o.grand_total, 
    o.gst_rate, 
    o.order_status, 
    o.payment_status, 
    o.shipping_method_name, 
    o.tracking_number, 
    o.created_at
FROM orders o
LEFT JOIN customers c ON c.id = o.user_id
WHERE o.id = $order_id
LIMIT 1
";
$order_res = $conn->query($order_sql);
if (!$order_res || $order_res->num_rows === 0) {
    die("Order not found");
}
$order = $order_res->fetch_assoc();

// fetch shipping address
$addr_sql = "SELECT first_name, last_name, address1, address2, city, state, zip_code, mobile
             FROM order_addresses
             WHERE order_id = $order_id AND `type` = 'shipping'
             LIMIT 1";
$addr_res = $conn->query($addr_sql);
$shipping_address = $addr_res && $addr_res->num_rows ? $addr_res->fetch_assoc() : null;

// fetch order items
$items_sql = "SELECT product_name, product_code, variation_id, product_type, image_path, unit_price, quantity, line_total, gst_rate
              FROM order_items
              WHERE order_id = $order_id";
$items_res = $conn->query($items_sql);
if ($items_res === false) {
    die("Failed to fetch order items");
}

// fetch latest payment (if any)
$pay_sql = "SELECT method, card_brand, card_last4, upi_id, amount, status, created_at
            FROM order_payments
            WHERE order_id = $order_id
            ORDER BY id DESC
            LIMIT 1";
$pay_res = $conn->query($pay_sql);
$payment = ($pay_res && $pay_res->num_rows) ? $pay_res->fetch_assoc() : null;

// handle status + courier update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'], $_POST['courier'])) {
    $updateOrderId = intval($_POST['order_id']);
    $newStatus = $_POST['new_status'];
    $courier = $_POST['courier'];

    $allowedStatuses = ['pending','ready_to_ship','shipped','delivered'];
    if (!in_array($newStatus, $allowedStatuses)) {
        die("Invalid status");
    }

    // Pick available tracking number
    if ($courier === 'stcourier') {
        $num_res = $conn->query("SELECT id, tracking_no FROM stcourier_numbers WHERE is_used = 0 LIMIT 1");
    } else { // indiapost
        $num_res = $conn->query("SELECT id, tracking_no FROM indiapost_numbers WHERE is_used = 0 LIMIT 1");
    }

    if ($num_res && $num_res->num_rows > 0) {
        $num_row = $num_res->fetch_assoc();
        $tracking_no = $num_row['tracking_no'];
        $tracking_id = $num_row['id'];

        // Update order
        $stmt = $conn->prepare("UPDATE orders SET order_status = ?, shipping_method_name = ?, tracking_number = ? WHERE id = ?");
        $stmt->bind_param("sssi", $newStatus, $courier, $tracking_no, $updateOrderId);
        if ($stmt->execute()) {
            // Mark number as used
            $tableName = $courier === 'stcourier' ? 'stcourier_numbers' : 'indiapost_numbers';
            $conn->query("UPDATE $tableName SET is_used = 1 WHERE id = $tracking_id");

            $stmt->close();
            header("Location: order_detail.php?order_id=" . $updateOrderId);
            exit;
        } else {
            echo "<script>alert('Failed to update order');</script>";
        }
    } else {
        echo "<script>alert('No available tracking number for selected courier');</script>";
    }
}

include 'header.php';
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Order Details</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order #<?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between gap-2 align-items-center">
                        <div>
                            <div class="card-title mb-1">Order Details</div>
                            <p class="mb-0 fs-12">
                                <span class="text-muted me-1">Ordered Date:</span>
                                <?= !empty($order['created_at']) ? date('d M, Y', strtotime($order['created_at'])) : '-'; ?>
                            </p>
                        </div>
                        <a href="pending.php" class="btn btn-primary btn-sm rounded-pill btn-w-md py-2">Go to List</a>
                    </div>

                    <div class="card-body">
                        <!-- Customer Details -->
                        <div class="row mb-3">
                            <div class="col-xl-6">
                                <div class="fs-15 fw-semibold mb-2">Customer Details:</div>
                                <div class="d-flex gap-5 flex-wrap">
                                    <div>
                                        <p class="mb-1 fw-semibold"><?= htmlspecialchars($order['user_name'] ?? 'Customer', ENT_QUOTES, 'UTF-8'); ?></p>
                                        <span class="text-muted mb-1 fs-12">Phone Number:</span>
                                        <div><?= htmlspecialchars($shipping_address['mobile'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                    <div class="mb-0">
                                        <span class="text-muted mb-1 fs-12">Shipping Method:</span>
                                        <div><?= htmlspecialchars($order['shipping_method_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></div>

                                        <?php if ($payment): ?>
                                            <div class="mt-2">
                                                <span class="text-muted mb-1 fs-12">Payment:</span>
                                                <div>
                                                    <?= htmlspecialchars(strtoupper($payment['method']), ENT_QUOTES, 'UTF-8'); ?>
                                                    <?= $payment['card_last4'] ? ' (last4: ' . htmlspecialchars($payment['card_last4'], ENT_QUOTES, 'UTF-8') . ')' : '' ?>
                                                    <?= $payment['upi_id'] ? ' - ' . htmlspecialchars($payment['upi_id'], ENT_QUOTES, 'UTF-8') : '' ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="col-xl-6">
                                <div class="fs-15 fw-semibold mb-2">Shipping Address:</div>
                                <?php if ($shipping_address): ?>
                                    <?php
                                        $addr_lines = [];
                                        $addr_lines[] = trim($shipping_address['first_name'] . ' ' . $shipping_address['last_name']);
                                        $addr_lines[] = $shipping_address['address1'];
                                        if (!empty($shipping_address['address2'])) $addr_lines[] = $shipping_address['address2'];
                                        $addr_lines[] = $shipping_address['city'] . ', ' . $shipping_address['state'] . ' - ' . $shipping_address['zip_code'];
                                    ?>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars(implode("\n", $addr_lines), ENT_QUOTES, 'UTF-8')); ?></p>
                                <?php else: ?>
                                    <p class="mb-0">No shipping address found</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ordered Products -->
                        <div class="fs-14 fw-semibold mb-4">Ordered Products:</div>
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Product</th>
                                        <th>Variation ID</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sno = 1;
                                    $computedGrand = 0.0;
                                    if ($items_res && $items_res->num_rows > 0):
                                        while ($item = $items_res->fetch_assoc()):
                                            $unit = (float)($item['unit_price'] ?? 0);
                                            $qty = (int)($item['quantity'] ?? 1);
                                            $lineTotal = (float)($item['line_total'] ?? ($unit * $qty));
                                            $computedGrand += $lineTotal;
                                    ?>
                                    <tr>
                                        <td><?= $sno++; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image_path'])): ?>
                                                    <div class="me-3">
                                                        <img src="<?= htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="product" style="width:60px;height:60px;object-fit:cover;border:1px solid #eee;">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="flex-fill">
                                                    <div class="mb-2 fs-16 fw-semibold"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    <div class="text-muted small"><?= htmlspecialchars($item['product_type'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($item['variation_id'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>₹<?= number_format($unit, 2); ?></td>
                                        <td><?= $qty; ?></td>
                                        <td class="text-center">₹<?= number_format($lineTotal, 2); ?></td>
                                    </tr>
                                    <?php
                                        endwhile;
                                    else:
                                        echo '<tr><td colspan="6" class="text-center">No items found for this order</td></tr>';
                                    endif;
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Sub Total:</td>
                                        <td class="text-center fw-bold">₹<?= number_format($computedGrand, 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Courier + Status Form -->
                        <?php if ($order['order_status'] === 'pending'): ?>
                        <form method="post" class="mt-3 d-flex flex-wrap align-items-center gap-2">
                            <input type="hidden" name="order_id" value="<?= (int)$order['id']; ?>">
                            <input type="hidden" name="new_status" value="ready_to_ship">
                        
                            <!-- Courier Select -->
                            <div class="flex-grow-1" style="min-width: 180px;">
                                <select name="courier" class="form-select form-select-sm" required>
                                    <option value="">--Select Courier--</option>
                                    <option value="stcourier">STCourier</option>
                                    <option value="indiapost">IndiaPost</option>
                                </select>
                            </div>
                        
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-truck me-1"></i> Ready To Ship
                            </button>
                        </form>
                        <?php else: ?>
                        <p class="mt-3">
                            Order is already <b><?= htmlspecialchars($order['order_status']); ?></b> 
                            with tracking: <b><?= htmlspecialchars($order['tracking_number'] ?? '-'); ?></b>
                        </p>
                        <?php endif; ?>


                        <div class="mt-3">
                            <a href="labeldownload.php?order_id=<?= (int)$order['id']; ?>" class="btn btn-outline-primary btn-sm">Download Label</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
