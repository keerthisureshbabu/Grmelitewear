<?php 
include 'db.php';
include 'header.php';

// ✅ Fetch all shipped orders
$sql = "SELECT o.id, o.order_number, o.shipping_method_name, o.tracking_number, 
               c.name AS customer_name
        FROM orders o
        JOIN customers c ON o.user_id = c.id
        WHERE o.order_status = 'shipped'
        ORDER BY o.id DESC";
$res = $conn->query($sql);

// ✅ Get order counts safely
$totalOrders   = ($conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc())['total'] ?? 0;
$pendingOrders = ($conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='pending'")->fetch_assoc())['total'] ?? 0;
$readyToShip   = ($conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='ready_to_ship'")->fetch_assoc())['total'] ?? 0;
$shippedOrders = ($conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status='shipped'")->fetch_assoc())['total'] ?? 0;
?>

<div class="main-content app-content">
  <div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
      <h1 class="page-title fw-semibold fs-18 mb-0">Shipped Orders</h1>
      <div class="ms-md-1 ms-0">
        <nav>
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">GRM Elite Wear</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- Page Header Close -->

    <!-- Order Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="all_orders.php">
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
    <!-- Order Statistics End -->

    <div class="card custom-card">
      <div class="card-header justify-content-between gap-2 align-items-center">
        <div class="card-title mb-0">📦 Shipped Orders</div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:60px;">S.No</th>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Courier</th>
                <th>Tracking Number</th>
                <th>Product Details</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($res && $res->num_rows > 0): ?>
                  <?php $sn = 1; while ($row = $res->fetch_assoc()): ?>
                      <tr>
                        <td><?= $sn++; ?></td>
                        <td>#<?= htmlspecialchars($row['order_number']); ?></td>
                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                        <td><?= htmlspecialchars($row['shipping_method_name']); ?></td>
                        <td><?= htmlspecialchars($row['tracking_number']); ?></td>
                        <td>
                          <a href="just_view.php?order_id=<?= urlencode($row['id']); ?>" 
                             class="btn btn-success btn-sm rounded-pill">
                            View
                          </a>
                        </td>
                      </tr>
                  <?php endwhile; ?>
              <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-danger">🚫 No shipped orders found.</td>
                  </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-footer border-top-0">
        <div class="d-flex justify-content-between">
            <div>Showing <b>1</b> to <b><?= ($res) ? $res->num_rows : 0; ?></b> entries</div>
            <ul class="pagination mb-0">
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
            </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
