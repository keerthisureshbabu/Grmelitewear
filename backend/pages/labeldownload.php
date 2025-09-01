<?php   
include 'db.php';

/* ============================
   1) Detect tracking column
   ============================ */
if (!function_exists('detectTrackingColumn')) {
    function detectTrackingColumn(mysqli $conn): string {
        $dbRes = $conn->query("SELECT DATABASE() AS dbname");
        $dbname = ($row = $dbRes?->fetch_assoc()) ? $row['dbname'] : '';

        foreach (['tracking_number', 'courier_detail'] as $col) {
            $stmt = $conn->prepare("
                SELECT COUNT(*) AS c
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'orders' AND COLUMN_NAME = ?
            ");
            $stmt->bind_param('ss', $dbname, $col);
            $stmt->execute();
            $exists = $stmt->get_result()->fetch_assoc()['c'] ?? 0;
            $stmt->close();
            if ((int)$exists > 0) return $col;
        }
        return 'tracking_number'; // default fallback
    }
}

$TRACKING_COL = detectTrackingColumn($conn);

/* ============================
   2) Build tracking URL
   ============================ */
if (!function_exists('buildTrackingUrl')) {
    function buildTrackingUrl(?string $courierCode, ?string $trackingNumber, ?string $orderNumber): string {
        $t = trim((string)$trackingNumber);
        $code = strtolower(trim((string)$courierCode));

        if ($t !== '') {
            switch ($code) {
                case 'stcourier':
                case 'st-courier':
                    return "https://stcourier.com/track-shipment?awb=" . rawurlencode($t);
                case 'indiapost':
                case 'india_post':
                case 'speed_post':
                    return "https://www.indiapost.gov.in/_layouts/15/DOP.Portal.Tracking/TrackConsignment.aspx?consignment=" . rawurlencode($t);
                default:
                    return "https://www.google.com/search?q=" . rawurlencode($code . " tracking " . $t);
            }
        }
        return "https://grmelitewear.com/track.php?order=" . rawurlencode((string)$orderNumber);
    }
}

/* ============================
   3) Handle tracking update (POST)
   ============================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tracking_number'])) {
    $updateOrderId = (int)$_POST['order_id'];
    $trackingNumber = trim((string)$_POST['tracking_number']);

    $col = in_array($TRACKING_COL, ['tracking_number', 'courier_detail'], true) ? $TRACKING_COL : 'tracking_number';
    $sql = "UPDATE orders SET {$col} = ?, order_status = 'shipped' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('Failed to prepare update statement');
    $stmt->bind_param('si', $trackingNumber, $updateOrderId);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $updateOrderId);
        exit;
    } else {
        echo "<script>alert('Failed to update tracking number');</script>";
    }
    $stmt->close();
}


/* ============================
   4) Fetch order + shipping address
   ============================ */
if (!isset($_GET['id'])) die("No order id given");
$order_id = (int)$_GET['id'];

$sql = "
SELECT 
    o.id,
    o.order_number,
    o.user_id,
    c.name AS user_name,
    o.shipping_method_name,
    o.subtotal_amount,
    o.shipping_amount,
    o.gst_rate,
    o.grand_total,
    " . $TRACKING_COL . " AS tracking_number,
    oa.first_name,
    oa.last_name,
    oa.address1,
    oa.address2,
    oa.city,
    oa.state,
    oa.zip_code,
    oa.mobile
FROM orders o
LEFT JOIN customers c ON c.id = o.user_id
LEFT JOIN order_addresses oa ON oa.order_id = o.id AND oa.type = 'shipping'
WHERE o.id = ?
LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) die("Failed to prepare fetch statement");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) die("Order not found");
$order = $res->fetch_assoc();
$stmt->close();

$trackingUrl = buildTrackingUrl('', $order['tracking_number'] ?? '', $order['order_number'] ?? '');

/* ============================
   5) Include header
   ============================ */
include 'header.php';
?>

<style>
body { font-family: Arial, sans-serif; color: #333; background: #f8f9fa; }
.bill { width: 148mm; min-height: 210mm; margin: 20px auto; border: 1px solid #ccc; background: #fff; }
.bill-header { display: flex; justify-content: space-between; align-items: flex-start; padding: 40px; border-bottom: 2px solid #000; }
.bill-logo img { width: 200px; margin-bottom: 1px; }
.bill-body { padding: 20px;  }
.bill-body .row { display: flex; justify-content: space-between; margin-bottom: 15px; }
.bill-body .col { width: 48%; }
.bill-body h3 { font-size: 16px; font-weight: bold; margin-bottom: 5px; margin-top: 10px; color: #333; }
.bill-body p { margin: 0 0 5px 0; font-size: 14px; }
.bill-footer { text-align: center; padding: 20px; font-size: 16px; font-weight: bold; border-top: 2px dashed #666; margin-top: 50px; }
.bill-footer h2 { margin: 0; color: #333; }
.shipping_add{ max-width:250px; }
@media print {
    @page { size: A5 portrait; margin: 10mm; }
    body * { visibility: hidden; }
    .bill, .bill * { visibility: visible; }
    .bill { position: absolute; left: 0; top: 0; width: 100%; border: none; margin: 0; }
}
</style>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="bill" id="labelRoot">
            <div class="bill-header">
                <div class="bill-logo">
                    <img src="../assets/images/logo/logo.jpg" alt="Logo">
                    <h6 style="color:#333; font-weight: bold;" >GRM ELITE WEAR,<br>KADAMPADI PO,<br>SULUR AERO, COIMBATORE – 641401<br>PH.NO: 8807144175</h6>
                </div>
                <div id="qrcode"></div>
            </div>
            <div class="bill-body">
                <!-- Courier & Order Number -->
                <div class="row">
                    <div class="col">
                        <h3>Courier:</h3>
                        <p><?= htmlspecialchars($order['shipping_method_name'] ?? '-') ?></p>
                    </div>
                    <div class="col">
                        <h3>Order No:</h3>
                        <p><?= htmlspecialchars($order['order_number'] ?? '-') ?></p>
                    </div>
                     <div class="col">
                        <h3>Tracking Number:</h3>
                        <p style="font-weight:bold; font-size: 18px; "><?= htmlspecialchars($order['tracking_number'] ?? '-') ?></p>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="row">
                    <div class="col">
                        <h3>Customer Name:</h3>
                        <p><?= htmlspecialchars($order['user_name'] ?? '-') ?></p>

                        <h3>Shipping Address:</h3>
                        <p class="shipping_add">
                        <?php
                            $fullname = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
                            echo htmlspecialchars($fullname) . "<br>";
                            $addressLines = array_filter([$order['address1'] ?? '', $order['address2'] ?? '', trim(($order['city'] ?? '') . ', ' . ($order['state'] ?? '') . ' ' . ($order['zip_code'] ?? ''))]);
                            echo nl2br(htmlspecialchars(implode("\n", $addressLines)));
                        ?>
                        </p>

                        <h3>Phone:</h3>
                        <p><?= htmlspecialchars($order['mobile'] ?? '-') ?></p>
                    </div>
                </div>

                <div class="bill-footer">
                    <h3>✨ Thank you for shopping with us! ✨</h3>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3 mt-2" style="margin-left: 600px;">
            <button onclick="downloadPDF()" class="btn btn-success btn-wave px-4 py-2 rounded-pill">📥 Download Label</button>
            <a href="readytoship.php" class="btn btn-secondary btn-wave px-4 py-2 rounded-pill">Back</a>
            
            <!-- Shipped Form -->
            <form method="post" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                <input type="hidden" name="tracking_number" value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>">
                <button type="submit" class="btn btn-success btn-wave px-4 py-2 rounded-pill"> Update To Shipped</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
window.onload = function() {
    new QRCode(document.getElementById("qrcode"), {
        text: <?= json_encode($trackingUrl) ?>,
        width: 150,
        height: 150
    });
};

function downloadPDF() {
    const element = document.getElementById('labelRoot');
    const opt = {
        margin: [0,0,0,0],
        filename: 'label_<?= preg_replace("/[^A-Za-z0-9-_]/", "_", ($order['order_number'] ?? 'order')) ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 3, useCORS: true },
        jsPDF: { unit: 'mm', format: [148,210], orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

<?php include 'footer.php'; ?>
