<?php
include 'db.php';
include 'header.php';

function getCount($conn, $query) {
    $res = $conn->query($query);
    $row = $res->fetch_assoc();
    return (int)array_values($row)[0];
}

// ------------------- DATE-WISE COUNTS -------------------
$data = [
    'today'        => getCount($conn, "SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()"),
    'yesterday'    => getCount($conn, "SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()-INTERVAL 1 DAY"),
    'this_week'    => getCount($conn, "SELECT COUNT(*) FROM orders WHERE YEARWEEK(created_at,1)=YEARWEEK(CURDATE(),1)"),
    'prev_week'    => getCount($conn, "SELECT COUNT(*) FROM orders WHERE YEARWEEK(created_at,1)=YEARWEEK(CURDATE()-INTERVAL 1 WEEK,1)"),
    'this_month'   => getCount($conn, "SELECT COUNT(*) FROM orders WHERE YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE())"),
    'prev_month'   => getCount($conn, "SELECT COUNT(*) FROM orders WHERE YEAR(created_at)=YEAR(CURDATE()-INTERVAL 1 MONTH) AND MONTH(created_at)=MONTH(CURDATE()-INTERVAL 1 MONTH)"),
    'last_3_months'=> getCount($conn, "SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)"),
    'last_6_months'=> getCount($conn, "SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)"),
    'last_year'    => getCount($conn, "SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)")
];

?>


<div class="main-content app-content">
  <div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
      <h1 class="page-title fw-semibold fs-18 mb-0">Order's Analysis Report</h1>
      <div class="ms-md-1 ms-0">
        <nav>
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Grmelitewear</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- Page Header Close -->

     <!-- Order Statistics -->
    <div class="row mb-4">
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="todayorderanalysis.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Today</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['today']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="yesterdayorder.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Yesterday</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['yesterday']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="thisweek.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">This Week</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['this_week']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="prevweek.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Prev Week</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['prev_week']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="thismonth.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">This Month</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['this_month']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="prevmonth.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Prev Month</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['prev_month']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="3_months.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Last 3 Months</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['last_3_months']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="6_months.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Last 6 Months</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['last_6_months']; ?> Orders</span>
                </a>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                <a href="1_year.php" class="card-body d-flex align-items-center gap-2">
                    <h6 class="mb-0">Last Year</h6>
                    <span class="badge bg-primary ms-auto"><?= $data['last_year']; ?> Orders</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Order Statistics End -->

    
<?php include('footer.php'); ?>