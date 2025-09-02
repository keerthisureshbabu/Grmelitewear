<?php
include("header.php");
// Easypost API integration
$easypost_api_key = 'YOUR_EASYPOST_API_KEY'; // Replace with your actual Easypost API key
include('../backend/pages/db.php');

$tracking_data = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_number = trim($_POST['order_number']);
    $mobile = trim($_POST['mobile']);

    if ($order_number && $mobile) {
        $stmt = $conn->prepare("SELECT o.*, oa.mobile FROM orders o JOIN order_addresses oa ON o.id = oa.order_id WHERE o.order_number = ? AND oa.mobile = ? AND oa.type = 'shipping'");
        $stmt->bind_param("ss", $order_number, $mobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $tracking_data = $result->fetch_assoc();
        } else {
            $error = "Order not found. Please check your Order Number and Mobile Number.";
        }
    } else {
        $error = "Please fill in both fields.";
    }
}
?>

<div class="container py-76" style="min-height: 80vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-main text-white text-center">
                    <h4 class="mb-0">Track Your Order</h4>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3 mb-4">
                        <div class="col-md-6">
                            <input type="text" name="order_number" class="form-control" placeholder="Order Number" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-main px-4">Track Order</button>
                        </div>
                    </form>

                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($tracking_data): ?>
                        <div class="mt-4">
                            <h5 class="mb-3 text-dark">Order Details</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Order ID:</strong> <?= htmlspecialchars($tracking_data['order_number']); ?></li>
                                <li class="list-group-item"><strong>Mobile:</strong> <?= htmlspecialchars($tracking_data['mobile']); ?></li>
                                <li class="list-group-item"><strong>Status:</strong> <?= htmlspecialchars(ucwords(str_replace('_', ' ', $tracking_data['order_status']))); ?></li>
                                <li class="list-group-item"><strong>Ordered On:</strong> <?= htmlspecialchars($tracking_data['created_at']); ?></li>

                                <?php if (!empty($tracking_data['courier_name']) && !empty($tracking_data['tracking_number'])): ?>
                                    <?php
                                    $courier_mapping = [
                                        'dtdc' => 'DTDC',
                                        'stcourier' => 'STCourier',
                                        'indianpost' => 'India_Post'
                                    ];
                                    $courier = strtolower(trim($tracking_data['shipping_method_name']));
                                    $carrier = isset($courier_mapping[$courier]) ? $courier_mapping[$courier] : '';
                                    ?>
                                    <li class="list-group-item"><strong>Courier:</strong> <?= htmlspecialchars($tracking_data['shipping_method_name']); ?></li>
                                    <li class="list-group-item"><strong>Tracking ID:</strong> <?= htmlspecialchars($tracking_data['tracking_number']); ?></li>
                                    <?php if ($carrier && $easypost_api_key !== 'YOUR_EASYPOST_API_KEY'): ?>
                                        <?php
                                        $url = 'https://api.easypost.com/v2/trackers';
                                        $data = json_encode([
                                            'tracker' => [
                                                'tracking_code' => $tracking_data['tracking_number'],
                                                'carrier' => $carrier
                                            ]
                                        ]);
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Authorization: Basic ' . base64_encode($easypost_api_key . ':'),
                                            'Content-Type: application/json'
                                        ]);
                                        $response = curl_exec($ch);
                                        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                        curl_close($ch);
                                        if ($http_code == 201) {
                                            $tracker = json_decode($response, true);
                                            $status = $tracker['status'];
                                            $tracking_details = $tracker['tracking_details'];
                                            echo '<li class="list-group-item"><strong>Tracking Status:</strong> ' . htmlspecialchars(ucwords(str_replace('_', ' ', $status))) . '</li>';
                                            if (!empty($tracking_details)) {
                                                $latest = end($tracking_details);
                                                echo '<li class="list-group-item"><strong>Latest Update:</strong> ' . htmlspecialchars($latest['message']) . ' at ' . htmlspecialchars($latest['datetime']) . '</li>';
                                            }
                                        } else {
                                            echo '<li class="list-group-item text-warning">Unable to fetch tracking information from Easypost.</li>';
                                        }
                                        ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-warning">Courier not supported or API key not set.</li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <li class="list-group-item text-warning text-center">
                                        Tracking information not available yet. Please check back later.
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
