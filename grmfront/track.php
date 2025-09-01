<?php
include("header.php");
include('../backend/pages/db.php');

$tracking_data = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_number = trim($_POST['order_number']);
    $mobile = trim($_POST['mobile']);

    if ($order_number && $mobile) {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND mobile_num = ?");
        $stmt->bind_param("is", $order_number, $mobile);
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
                                <li class="list-group-item"><strong>Order ID:</strong> <?= htmlspecialchars($tracking_data['order_id']); ?></li>
                                <li class="list-group-item"><strong>Mobile:</strong> <?= htmlspecialchars($tracking_data['mobile_num']); ?></li>
                                <li class="list-group-item"><strong>Status:</strong> <?= htmlspecialchars(ucwords(str_replace('_', ' ', $tracking_data['status']))); ?></li>
                                <li class="list-group-item"><strong>Ordered On:</strong> <?= htmlspecialchars($tracking_data['order_date']); ?></li>

                                <?php if (!empty($tracking_data['courier_name']) && !empty($tracking_data['tracking_number'])): ?>
                                    <?php
                                    $courier = strtolower(trim($tracking_data['courier_name']));
                                    $tracking_id = urlencode($tracking_data['tracking_number']);

                                    // Assign proper tracking link & logo
                                    if ($courier === 'dtdc') {
                                        $tracking_link = "https://www.dtdc.in/tracking/tracking_results.asp?cnno={$tracking_id}";
                                        $courier_img = "assets/images/track/dtdc.png";
                                    } elseif ($courier === 'stcourier') {
                                        $tracking_link = "https://stcourier.com/track?tracking_number={$tracking_id}";
                                        $courier_img = "assets/images/track/stcourier.png";
                                    } elseif ($courier === 'indianpost') {
                                        $tracking_link = "https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?consignmentno={$tracking_id}";
                                        $courier_img = "assets/images/track/indianpost.png";
                                    } else {
                                        $tracking_link = "#";
                                        $courier_img = "";
                                    }
                                    ?>
                                    <li class="list-group-item"><strong>Courier:</strong> <?= htmlspecialchars($tracking_data['courier_name']); ?></li>
                                    <li class="list-group-item"><strong>Tracking ID:</strong> <?= htmlspecialchars($tracking_data['tracking_number']); ?></li>
                                    <?php if (!empty($courier_img)): ?>
                                        <li class="list-group-item text-center">
                                            <a href="<?= $tracking_link ?>" target="_blank" class="btn btn-outline-primary">
                                                <img src="<?= htmlspecialchars($courier_img); ?>" style="max-height:60px;" alt="<?= htmlspecialchars($tracking_data['courier_name']); ?>">
                                                <br><small>Click to track on <?= htmlspecialchars(ucwords($tracking_data['courier_name'])); ?></small>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <li class="list-group-item text-warning text-center">
                                        Tracking information not available yet. Please check back later.
                                    </li>
                                <?php endif; ?>
                            </ul>
                            
                            <!-- Additional courier tracking links -->
                            <div class="mt-4">
                                <h6 class="mb-3 text-dark">Track with Other Couriers</h6>
                                <div class="row justify-content-center">
                                    <div class="col-md-4 text-center mb-2">
                                        <a href="https://stcourier.com/track" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <img src="assets/images/track/stcourier.png" style="max-height:40px;" alt="ST Courier">
                                        </a>
                                    </div>
                                    <div class="col-md-4 text-center mb-2">
                                        <a href="https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <img src="assets/images/track/indianpost.png" style="max-height:40px;" alt="Indian Post">
                                        </a>
                                    </div>
                                    <div class="col-md-4 text-center mb-2">
                                        <a href="https://dtdc.com/track" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <img src="assets/images/track/dtdc.png" style="max-height:40px;" alt="DTDC">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
