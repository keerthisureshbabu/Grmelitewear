<?php
include 'db.php';
include("header.php");

// ----------------- COUNT BOXES -----------------
// new customer count
$newCountSql = "
SELECT COUNT(*) AS total
FROM customers c
LEFT JOIN order_addresses o ON c.mobile_num = o.mobile
WHERE o.mobile IS NULL
";
$newCountRes = $conn->query($newCountSql);
$newcustomer = $newCountRes->fetch_assoc()['total'] ?? 0;

// old customer count
$oldCountSql = "
SELECT COUNT(DISTINCT c.id) AS total
FROM customers c
JOIN order_addresses o ON c.mobile_num = o.mobile
";
$oldCountRes = $conn->query($oldCountSql);
$oldcustomer = $oldCountRes->fetch_assoc()['total'] ?? 0;

// instagram count
$instagramSql = "SELECT COUNT(*) AS total FROM customers WHERE origin='instagram'";
$instagram = $conn->query($instagramSql)->fetch_assoc()['total'] ?? 0;

// facebook count
$facebookSql = "SELECT COUNT(*) AS total FROM customers WHERE origin='facebook'";
$facebook = $conn->query($facebookSql)->fetch_assoc()['total'] ?? 0;

// google count
$googleSql = "SELECT COUNT(*) AS total FROM customers WHERE origin='google'";
$google = $conn->query($googleSql)->fetch_assoc()['total'] ?? 0;

// direct count
$directSql = "SELECT COUNT(*) AS total FROM customers WHERE origin='direct'";
$direct = $conn->query($directSql)->fetch_assoc()['total'] ?? 0;

// ----------------- MAIN DATA for Instagram customers -----------------
$sqlgoogle = "
SELECT id, origin, name, mobile_num, created_at
FROM customers
WHERE origin='google'
ORDER BY created_at DESC
";
$resultgoogle = $conn->query($sqlgoogle);
?>

<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Customers by Origin</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Instagram</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3">
                <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                    <a href="newcustomer.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <h6 class="mb-0">New Customer's</h6>
                            <span class="badge bg-primary ms-auto"><?= $newcustomer; ?> Customers</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card custom-card border border-warning border-opacity-25 bg-warning-transparent">
                    <a href="oldcustomer.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <h6 class="mb-0">Old Customer's</h6>
                            <span class="badge bg-warning ms-auto"><?= $oldcustomer; ?> Customers</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card custom-card border border-info border-opacity-10 bg-info-transparent">
                    <a href="instagram.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <h6 class="mb-0">Origin - Instagram</h6>
                            <span class="badge bg-info ms-auto"><?= $instagram; ?> Customers</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card custom-card border border-success border-opacity-10 bg-success-transparent">
                    <a href="facebook.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <h6 class="mb-0">Origin - Facebook</h6>
                            <span class="badge bg-success ms-auto"><?= $facebook; ?> Customers</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                    <a href="google.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <h6 class="mb-0">Origin - Google</h6>
                            <span class="badge bg-primary ms-auto"><?= $google; ?> Customers</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card custom-card border border-warning border-opacity-10 bg-warning-transparent">
                    <a href="direct.php">
                        <div class="card-body d-flex align-items-center gap-2">
                            <h6 class="mb-0">Origin - Direct</h6>
                            <span class="badge bg-warning ms-auto"><?= $direct; ?> Customers</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Statistics Cards End -->

        <!-- Instagram Customers Table -->
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Google Customers</div>
                <div class="input-group p-3 bg-light rounded" style="max-width:300px;">
                    <input type="text" class="form-control" placeholder="Search by Mobile Number...">
                    <button class="btn btn-primary"><i class="ti ti-search"></i></button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>S.no</th>
                                <th>Origin</th>
                                <th>Customer Name</th>
                                <th>Mobile Number</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $sno = 1;
                        if ($resultgoogle && $resultgoogle->num_rows > 0): 
                            while($row = $resultgoogle->fetch_assoc()): ?>
                            <tr>
                                <td><?= $sno++; ?></td>
                                <td><?= htmlspecialchars($row['origin']); ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= htmlspecialchars($row['mobile_num']); ?></td>
                                <td><?= !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '-'; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">No Facebook customers found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer border-top-0">
                <div class="d-flex justify-content-between">
                    <div>Showing <b>1</b> to <b><?= $resultgoogle ? $resultgoogle->num_rows : 0 ?></b> entries</div>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Instagram Customers Table End -->

    </div>
</div>

<?php include("footer.php"); ?>
