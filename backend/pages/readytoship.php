<?php  
// readytoship.php
include 'db.php';
include 'header.php';

// Counts (use orders.order_status)
$totalOrders    = (int) ($conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'] ?? 0);
$pendingOrders  = (int) ($conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='pending'")->fetch_assoc()['total'] ?? 0);
$readyToShip    = (int) ($conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='ready_to_ship'")->fetch_assoc()['total'] ?? 0);
$shippedOrders  = (int) ($conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='shipped'")->fetch_assoc()['total'] ?? 0);

// ✅ Main list query – use orders.tracking_number
$sql = "
SELECT 
    o.id, 
    o.order_number, 
    c.name AS customer_name, 
    c.mobile_num AS customer_mobile, 
    o.shipping_method_name, 
    o.tracking_number, 
    o.created_at
FROM orders AS o
LEFT JOIN customers AS c ON o.user_id = c.id
WHERE o.order_status = 'ready_to_ship'
ORDER BY o.created_at DESC
";

$ordersResult = $conn->query($sql);
?>
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Ready To Ship Orders</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ready To Ship</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3">
                <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                    <a href="#">
                        <div class="card-body d-flex align-items-center gap-2">
                            <span><i class="ri-box-2-fill fs-5 lh-1 text-primary"></i></span>
                            <h6 class="mb-0">All Orders</h6>
                            <span class="badge bg-primary ms-auto"><?= $totalOrders; ?> Orders</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card custom-card border border-warning border-opacity-25 bg-warning-transparent">
                    <a href="pending.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <span><i class="ri-timer-2-line fs-5 lh-1 text-warning"></i></span>
                            <h6 class="mb-0">Pending</h6>
                            <span class="badge bg-warning ms-auto"><?= $pendingOrders; ?> Orders</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card custom-card border border-info border-opacity-10 bg-info-transparent">
                    <a href="readytoship.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <span><i class="ri-truck-line fs-5 lh-1 text-info"></i></span>
                            <h6 class="mb-0">Ready To Ship</h6>
                            <span class="badge bg-info ms-auto"><?= $readyToShip; ?> Orders</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card custom-card border border-success border-opacity-10 bg-success-transparent">
                    <a href="shipped.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <span><i class="ri-check-double-line fs-5 lh-1 text-success"></i></span>
                            <h6 class="mb-0">Shipped</h6>
                            <span class="badge bg-success ms-auto"><?= $shippedOrders; ?> Orders</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Ready To Ship Orders Table -->
        <div class="card custom-card">
            <div class="card-header justify-content-between gap-2 align-items-center">
                <div class="card-title">Ready To Ship Orders</div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-nowrap align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>S.No</th>
                                <th>Order No</th>
                                <th>Customer Name</th>
                                <th>Mobile</th>
                                <th>Shipping Method</th>
                                <th>Tracking Number</th>
                                <th>Ordered Date</th>
                                <th>Download Label</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($ordersResult && $ordersResult->num_rows > 0) {
                                $sno = 1;
                                while ($row = $ordersResult->fetch_assoc()) {
                                    $orderId = (int)$row['id'];
                                    $orderNumber = htmlspecialchars($row['order_number'], ENT_QUOTES, 'UTF-8');
                                    $customerName = htmlspecialchars($row['customer_name'], ENT_QUOTES, 'UTF-8');
                                    $mobile = htmlspecialchars($row['customer_mobile'], ENT_QUOTES, 'UTF-8');
                                    $shippingMethod = htmlspecialchars($row['shipping_method_name'] ?? '-', ENT_QUOTES, 'UTF-8');
                                    $trackingNumber = htmlspecialchars($row['tracking_number'] ?? '-', ENT_QUOTES, 'UTF-8');
                                    $orderedDate = !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '-';
                                    ?>
                                    <tr>
                                        <td><?= $sno++; ?></td>
                                        <td>#<?= $orderNumber; ?></td>
                                        <td><?= $customerName; ?></td>
                                        <td><?= $mobile; ?></td>
                                        <td><?= $shippingMethod; ?></td>
                                        <td><?= $trackingNumber; ?></td>
                                        <td><?= $orderedDate; ?></td>
                                        <td>
                                            <a href="labeldownload.php?id=<?= $orderId; ?>" class="btn btn-sm btn-primary">
                                                Download Label
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No Ready To Ship orders found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer border-top-0 mt-3">
                <div class="d-flex justify-content-between">
                    <div>Showing <b>1</b> to <b><?= $ordersResult ? $ordersResult->num_rows : 0 ?></b> entries</div>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
