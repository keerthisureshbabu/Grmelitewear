<?php

include 'db.php';

function detectTrackingColumn(mysqli $conn): string {
    $dbRes = $conn->query("SELECT DATABASE() AS dbname");
    $row = $dbRes ? $dbRes->fetch_assoc() : null;
    $dbname = $row ? $row['dbname'] : '';

    $col = 'tracking_number';
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS c
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'orders' AND COLUMN_NAME = ?
    ");
    $stmt->bind_param('ss', $dbname, $col);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->fetch_assoc()['c'] ?? 0;
    $stmt->close();
    if ((int)$exists > 0) return $col;

    // fallback to courier_detail
    $col = 'courier_detail';
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS c
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'orders' AND COLUMN_NAME = ?
    ");
    $stmt->bind_param('ss', $dbname, $col);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->fetch_assoc()['c'] ?? 0;
    $stmt->close();
    return ((int)$exists > 0) ? 'courier_detail' : 'tracking_number';
}
$TRACKING_COL = detectTrackingColumn($conn);

// --- function: assign tracking from pool for a courier (transactionally) ---
function assignTrackingFromPool(mysqli $conn, string $courier_code, int $order_id, string $tracking_col): array {
    // returns ['success' => bool, 'message' => string, 'tracking' => string|null]
    $courier_code = trim($courier_code);
    if ($courier_code === '') return ['success' => false, 'message' => 'No courier code provided', 'tracking' => null];

    try {
        // Start transaction
        $conn->begin_transaction();

        // 1) pick one available tracking number FOR UPDATE (lock it)
        $sql = "SELECT id, tracking_number FROM tracking_numbers
                WHERE courier_code = ? AND status = 'available'
                ORDER BY id ASC
                LIMIT 1
                FOR UPDATE";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
        $stmt->bind_param('s', $courier_code);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if (!$row) {
            $conn->rollback();
            return ['success' => false, 'message' => 'No available tracking numbers for ' . $courier_code, 'tracking' => null];
        }

        $tn_id = (int)$row['id'];
        $tn_value = $row['tracking_number'];

        // 2) mark as used and set assigned_order_id
        $up = $conn->prepare("UPDATE tracking_numbers SET status = 'used', assigned_order_id = ?, assigned_at = NOW() WHERE id = ?");
        if (!$up) throw new Exception('Prepare failed (update pool): ' . $conn->error);
        $up->bind_param('ii', $order_id, $tn_id);
        if (!$up->execute()) {
            $up->close();
            throw new Exception('Failed to update tracking_numbers: ' . $up->error);
        }
        $up->close();

        // 3) write tracking into orders table (use the detected column)
        $col = $tracking_col;
        // safeguard column name (only allow expected names)
        if (!in_array($col, ['tracking_number','courier_detail'], true)) {
            // fallback
            $col = 'tracking_number';
        }
        $q = "UPDATE orders SET {$col} = ?, updated_at = NOW() WHERE id = ?";
        $u2 = $conn->prepare($q);
        if (!$u2) throw new Exception('Prepare failed (update orders): ' . $conn->error);
        $u2->bind_param('si', $tn_value, $order_id);
        if (!$u2->execute()) {
            $u2->close();
            throw new Exception('Failed to update orders: ' . $u2->error);
        }
        $u2->close();

        // commit
        $conn->commit();
        return ['success' => true, 'message' => 'Assigned ' . $tn_value, 'tracking' => $tn_value];
    } catch (Exception $e) {
        if ($conn->errno) {
            // If transaction is active, rollback
            $conn->rollback();
        }
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'tracking' => null];
    }
}

// --- Handle POST actions (assign-from-pool or manual update) ---
// Must be before any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // assign from pool action
    if (isset($_POST['assign_from_pool'], $_POST['order_id'])) {
        $order_id = (int)$_POST['order_id'];

        // fetch order's shipping_method_code to know which courier pool to use
        $s = $conn->prepare("SELECT shipping_method_code FROM orders WHERE id = ? LIMIT 1");
        $s->bind_param('i', $order_id);
        $s->execute();
        $rs = $s->get_result();
        $orderRow = $rs->fetch_assoc();
        $s->close();
        $courier = $orderRow['shipping_method_code'] ?? '';

        $assignRes = assignTrackingFromPool($conn, $courier, $order_id, $TRACKING_COL);
        // redirect back with message (simple GET param)
        $msg = rawurlencode($assignRes['message']);
        header("Location: " . $_SERVER['PHP_SELF'] . "?id={$order_id}&msg={$msg}");
        exit;
    }

    // manual update of tracking number (admin enters tracking number)
    if (isset($_POST['manual_update'], $_POST['order_id'], $_POST['tracking_number'])) {
        $order_id = (int)$_POST['order_id'];
        $tn = trim($_POST['tracking_number']);
        $col = $TRACKING_COL;
        if (!in_array($col, ['tracking_number','courier_detail'], true)) $col = 'tracking_number';

        $upd = $conn->prepare("UPDATE orders SET {$col} = ?, updated_at = NOW() WHERE id = ?");
        $upd->bind_param('si', $tn, $order_id);
        $ok = $upd->execute();
        $upd->close();
        $msg = $ok ? rawurlencode('Manual update saved') : rawurlencode('Failed to save manual tracking');
        header("Location: " . $_SERVER['PHP_SELF'] . "?id={$order_id}&msg={$msg}");
        exit;
    }
}

// --- Fetch order + shipping address for display ---
if (!isset($_GET['id'])) {
    die("No order id given");
}
$order_id = (int)$_GET['id'];

$sql = "
SELECT 
    o.id,
    o.order_number,
    o.user_name,
    o.shipping_method_code,
    o.shipping_method_name,
    o.seller_state,
    o.buyer_state,
    o.subtotal_amount,
    o.shipping_amount,
    o.grand_total,
    o." . $TRACKING_COL . " AS tracking_number,
    oa.first_name,
    oa.last_name,
    oa.address1,
    oa.address2,
    oa.city,
    oa.state,
    oa.zip_code,
    oa.mobile
FROM orders o
LEFT JOIN order_addresses oa 
       ON oa.order_id = o.id AND oa.type = 'shipping'
WHERE o.id = ?
LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) die('Prepare failed: ' . $conn->error);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) die('Order not found');
$order = $res->fetch_assoc();
$stmt->close();

// optional message
$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';

// --- Now include header and show UI ---
include 'header.php';
?>

<!-- minimal styles kept short for brevity -->
<style>
.container { padding: 20px; }
.actions { margin-top: 12px; display:flex; gap:8px; align-items:center; }
.form-control { padding:6px 8px; border:1px solid #ccc; border-radius:4px; }
.btn { padding:8px 12px; border-radius:6px; cursor:pointer; border:none; }
.btn-primary { background:#0d6efd; color:#fff; }
.btn-warning { background:#ffc107; color:#000; }
.btn-secondary { background:#6c757d; color:#fff; }
.note { font-size:0.9rem; color:#666; margin-top:8px; }
</style>

<div class="container">
    <h2>Order #<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></h2>
    <?php if ($msg): ?>
        <div style="background:#e9f7ef;border-left:4px solid #0b8a4d;padding:8px;margin-bottom:8px;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <p><strong>Courier Method:</strong> <?php echo htmlspecialchars($order['shipping_method_name'] ?? $order['shipping_method_code'] ?? ''); ?></p>

    <p><strong>Tracking (order):</strong>
        <?php
            $tn = trim((string)($order['tracking_number'] ?? ''));
            echo $tn !== '' ? htmlspecialchars($tn) : '<em>Not assigned</em>';
        ?>
    </p>

    <!-- ASSIGN FROM POOL (only show for couriers that use pool) -->
    <?php
    // couriers which we manage via pool
    $poolCouriers = ['st_courier','stcourier','st-courier','stcourier','indian_post','india_post','speed_post'];
    $currentCourierCode = strtolower(trim((string)($order['shipping_method_code'] ?? '')));

    $canAssign = in_array($currentCourierCode, $poolCouriers, true) && ($tn === '');
    ?>

    <div class="actions">
        <?php if ($canAssign): ?>
            <form method="post" action="" style="display:inline;">
                <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                <input type="hidden" name="assign_from_pool" value="1">
                <button class="btn btn-primary" type="submit">Assign tracking from pool</button>
            </form>
            <span class="note">Will pick one available number for <?php echo htmlspecialchars($currentCourierCode); ?> and assign to this order (not shown to public until you publish).</span>
        <?php else: ?>
            <span class="note">
                <?php if ($tn === ''): ?>
                    Assign-from-pool not available for this courier or tracking already set.
                <?php else: ?>
                    Tracking assigned.
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Manual override/update (admins only) -->
    <div style="margin-top:12px;">
        <form method="post" action="" style="display:flex; gap:8px; align-items:center;">
            <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
            <input type="hidden" name="manual_update" value="1">
            <input class="form-control" type="text" name="tracking_number" placeholder="Enter tracking number (manual)" value="<?php echo htmlspecialchars($order['tracking_number'] ?? ''); ?>" style="min-width:260px;">
            <button class="btn btn-warning" type="submit">Save Manual</button>
        </form>
        <div class="note">Use manual only if you want to override the pool value or enter a courier-provided AWB.</div>
    </div>

    <hr>

    <!-- Print label / receipt actions (keeps your previous UI) -->
    <div style="margin-top:12px;">
        <button onclick="window.print()" class="btn btn-secondary">Print Label</button>
        <a href="readytoship.php" class="btn" style="background:#f0f0f0;margin-left:8px;">Back</a>
    </div>
</div>

<?php include 'footer.php'; ?>
