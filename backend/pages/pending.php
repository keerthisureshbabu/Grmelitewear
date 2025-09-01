<?php 
ob_start();
include 'db.php';
include 'header.php';

// Counts
$totalOrders    = (int) $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$pendingOrders  = (int) $conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='pending'")->fetch_assoc()['total'];
$readyToShip    = (int) $conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='ready_to_ship'")->fetch_assoc()['total'];
$shippedOrders  = (int) $conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='shipped'")->fetch_assoc()['total'];

// Handle courier selection / Ready to Ship
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['order_id'], $_POST['courier'])) {
    $order_id = intval($_POST['order_id']);
    $courier = $_POST['courier'];

    $tracking_no = null;
    $tracking_id = null;

    if($courier==='stcourier'){
        $res = $conn->query("SELECT id, tracking_no 
                             FROM stcourier_numbers 
                             WHERE is_used=0 
                             ORDER BY id ASC 
                             LIMIT 1");
        if($res && $res->num_rows>0){
            $row = $res->fetch_assoc();
            $tracking_no = $row['tracking_no'];
            $tracking_id = $row['id'];
        }
    } elseif($courier==='indiapost'){
        $res = $conn->query("SELECT id, tracking_no 
                             FROM indiapost_numbers 
                             WHERE is_used=0 
                             ORDER BY id ASC 
                             LIMIT 1");
        if($res && $res->num_rows>0){
            $row = $res->fetch_assoc();
            $tracking_no = $row['tracking_no'];
            $tracking_id = $row['id'];
        }
    }

    if($tracking_no){
        $conn->begin_transaction();
        try {
            // Update order
            $stmt = $conn->prepare("UPDATE orders 
                                    SET order_status='ready_to_ship', 
                                        shipping_method_name=?, 
                                        tracking_number=? 
                                    WHERE id=?");
            $stmt->bind_param("ssi",$courier,$tracking_no,$order_id);
            $stmt->execute();
            $stmt->close();

            // Mark tracking number as used
            if($courier==='stcourier'){
                $conn->query("UPDATE stcourier_numbers SET is_used=1 WHERE id=$tracking_id");
            } else {
                $conn->query("UPDATE indiapost_numbers SET is_used=1 WHERE id=$tracking_id");
            }

            $conn->commit();
            header("Location: pending.php");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error assigning tracking number');</script>";
        }
    } else {
        echo "<script>alert('No available tracking numbers for selected courier');</script>";
    }
}

// Fetch pending orders with product info
$sql = "
SELECT 
    o.id AS order_id,
    o.order_number,
    o.user_id,
    c.name AS user_name,
    oa.mobile AS mobile_num,
    oa.zip_code,
    o.created_at AS order_date,
    o.order_status AS status,
    GROUP_CONCAT(CONCAT(i.product_name,' (x',i.quantity,')') SEPARATOR ' || ') AS products,
    MIN(i.image_path) AS image,
    o.grand_total AS total_amount
FROM orders o
LEFT JOIN customers c ON c.id=o.user_id
LEFT JOIN order_addresses oa ON oa.order_id=o.id AND oa.type='shipping'
LEFT JOIN order_items i ON i.order_id=o.id
WHERE o.order_status='pending'
GROUP BY o.id
ORDER BY o.created_at DESC
";

$result = $conn->query($sql);
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Pending Orders</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pending Orders</li>
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

        <!-- Orders Table -->
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Pending Orders</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order No</th>
                                <th>Product(s)</th>
                                <th>Customer</th>
                                <th>Mobile</th>
                                <th>Pincode</th>
                                <th>Ordered Date</th>
                                <th>Status</th>
                                <th>Cost</th>
                                <th>Courier</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($result && $result->num_rows>0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($row['order_number']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm avatar-square bg-gray-300">
                                            <?php if(!empty($row['image'])): ?>
                                                <img src="<?= htmlspecialchars($row['image']); ?>" alt="product" class="w-100 h-100">
                                            <?php else: ?>
                                                <img src="default-product.png" alt="product" class="w-100 h-100">
                                            <?php endif; ?>
                                        </span>
                                        <div class="ms-2">
                                            <p class="fw-semibold mb-0">
                                                <a href="order_detail.php?order_id=<?= (int)$row['order_id']; ?>">
                                                    <?= htmlspecialchars(explode(' || ',$row['products'])[0] ?? 'Product'); ?>
                                                </a>
                                            </p>
                                            <small class="text-muted">Items: <?= htmlspecialchars($row['products']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['user_name'] ?: $row['user_id']); ?></td>
                                <td><?= htmlspecialchars($row['mobile_num']); ?></td>
                                <td><?= htmlspecialchars($row['zip_code']); ?></td>
                                <td><?= !empty($row['order_date']) ? date('d M Y', strtotime($row['order_date'])) : '-'; ?></td>
                                <td><span class="badge bg-warning"><?= ucfirst(str_replace('_',' ',$row['status'])); ?></span></td>
                                <td>₹<?= number_format($row['total_amount'],2); ?></td>
                                <td>
                                    <form method="post" class="d-flex gap-2">
                                        <input type="hidden" name="order_id" value="<?= (int)$row['order_id']; ?>">
                                        <select name="courier" class="form-select form-select-sm" required>
                                            <option value="">Select Courier</option>
                                            <option value="stcourier">STCourier</option>
                                            <option value="indiapost">IndiaPost</option>
                                        </select>
                                </td>
                                <td>
                                        <button type="submit" class="btn btn-sm btn-success">Ready to Ship</button>
                                        <a href="order_detail.php?order_id=<?= (int)$row['order_id']; ?>" class="btn btn-sm btn-primary ms-1">View</a>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="10" class="text-center py-4">No pending orders found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include 'footer.php';
ob_end_flush();
?>
