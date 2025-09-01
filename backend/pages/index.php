<?php
session_start();
include_once("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Today date (YYYY-MM-DD)
$today = date('Y-m-d');

// ✅ Total orders today
$totalOrdersQuery = $conn->query("SELECT COUNT(*) AS total_orders FROM orders WHERE DATE(created_at) = '$today'");
$totalOrders = ($totalOrdersQuery->fetch_assoc())['total_orders'];

$revenueQuery = $conn->query("
    SELECT SUM(oi.unit_price * oi.quantity) AS total_revenue
    FROM order_items oi
    INNER JOIN orders o ON oi.order_id = o.id
    WHERE DATE(o.created_at) = '$today'
");
$totalRevenue = ($revenueQuery->fetch_assoc())['total_revenue'];
if ($totalRevenue == null) $totalRevenue = 0;

// ✅ Count from each table
$kidsCount = ($conn->query("SELECT COUNT(*) AS c FROM kids_products")->fetch_assoc())['c'];
$womenCount = ($conn->query("SELECT COUNT(*) AS c FROM womens_products")->fetch_assoc())['c'];
$toysCount = ($conn->query("SELECT COUNT(*) AS c FROM toys_products")->fetch_assoc())['c'];
$accessoriesCount = ($conn->query("SELECT COUNT(*) AS c FROM accessories_products")->fetch_assoc())['c'];

// ✅ Total products
$totalProducts = $kidsCount + $womenCount + $toysCount + $accessoriesCount;

// new customer count
$newCountSql = "
SELECT COUNT(*) AS total
FROM customers c
LEFT JOIN order_addresses o ON c.mobile_num = o.mobile
WHERE o.mobile IS NULL
";
$newCountRes = $conn->query($newCountSql);
$newcustomer = $newCountRes->fetch_assoc()['total'] ?? 0;

include("header.php");
?>

    <!-- MAIN-CONTENT -->
            
    <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <h1 class="page-title fw-semibold fs-18 mb-0">Grmelitewear</h1>
                    <div class="ms-md-1 ms-0">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Grmelitewear</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- Page Header Close -->
                <!-- Start::row-1 -->
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                                                                       <div>
                                                <div class="fs-13 align-middle text-muted mb-1"> Today Total Orders</div>
                                                <div class="text-muted fs-12 mb-1">
                                                    <span class="text-dark fw-semibold fs-18">
                                                         <?php echo $totalOrders ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-primary p-1 rounded-circle border border-primary border-opacity-25 shadow-sm ms-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="p-2 bg-primary bg-opacity-25 rounded-circle" width="40px" height="40px" viewBox="0 0 24 24"><path fill="currentColor" d="M8.422 20.618C10.178 21.54 11.056 22 12 22V12L2.638 7.073l-.04.067C2 8.154 2 9.417 2 11.942v.117c0 2.524 0 3.787.597 4.801c.598 1.015 1.674 1.58 3.825 2.709z"/><path fill="currentColor" d="m17.577 4.432l-2-1.05C13.822 2.461 12.944 2 12 2c-.945 0-1.822.46-3.578 1.382l-2 1.05C4.318 5.536 3.242 6.1 2.638 7.072L12 12l9.362-4.927c-.606-.973-1.68-1.537-3.785-2.641" opacity="0.7"/><path fill="currentColor" d="m21.403 7.14l-.041-.067L12 12v10c.944 0 1.822-.46 3.578-1.382l2-1.05c2.151-1.129 3.227-1.693 3.825-2.708c.597-1.014.597-2.277.597-4.8v-.117c0-2.525 0-3.788-.597-4.802" opacity="0.5"/><path fill="currentColor" d="m6.323 4.484l.1-.052l1.493-.784l9.1 5.005l4.025-2.011q.205.232.362.498c.15.254.262.524.346.825L17.75 9.964V13a.75.75 0 0 1-1.5 0v-2.286l-3.5 1.75v9.44A3 3 0 0 1 12 22c-.248 0-.493-.032-.75-.096v-9.44l-8.998-4.5c.084-.3.196-.57.346-.824q.156-.266.362-.498l9.04 4.52l3.387-1.693z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                            <div>
                                                <div class="fs-13 align-middle text-muted mb-1"> Today Total Revenue</div>
                                                <div class="text-muted fs-12 mb-1">
                                                    <span class="text-dark fw-semibold fs-18">
                                                         <?php echo $totalRevenue ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-secondary p-1 rounded-circle border border-secondary border-opacity-25 shadow-sm ms-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="p-2 bg-secondary bg-opacity-25 rounded-circle" width="40px" height="40px" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12.052 1.25h-.104c-.899 0-1.648 0-2.242.08c-.628.084-1.195.27-1.65.725c-.456.456-.642 1.023-.726 1.65c-.057.427-.074 1.446-.078 2.32c-2.022.067-3.237.303-4.08 1.147C2 8.343 2 10.229 2 14s0 5.657 1.172 6.828S6.229 22 10 22h4c3.771 0 5.657 0 6.828-1.172S22 17.771 22 14s0-5.657-1.172-6.828c-.843-.844-2.058-1.08-4.08-1.146c-.004-.875-.02-1.894-.078-2.32c-.084-.628-.27-1.195-.726-1.65c-.455-.456-1.022-.642-1.65-.726c-.594-.08-1.344-.08-2.242-.08m3.196 4.752c-.005-.847-.019-1.758-.064-2.097c-.063-.461-.17-.659-.3-.789s-.328-.237-.79-.3c-.482-.064-1.13-.066-2.094-.066s-1.612.002-2.095.067c-.461.062-.659.169-.789.3s-.237.327-.3.788c-.045.34-.06 1.25-.064 2.097Q9.34 5.999 10 6h4q.662 0 1.248.002M12 9.25a.75.75 0 0 1 .75.75v.01c1.089.274 2 1.133 2 2.323a.75.75 0 0 1-1.5 0c0-.384-.426-.916-1.25-.916s-1.25.532-1.25.916s.426.917 1.25.917c1.385 0 2.75.96 2.75 2.417c0 1.19-.911 2.048-2 2.323V18a.75.75 0 0 1-1.5 0v-.01c-1.089-.274-2-1.133-2-2.323a.75.75 0 0 1 1.5 0c0 .384.426.916 1.25.916s1.25-.532 1.25-.916s-.426-.917-1.25-.917c-1.385 0-2.75-.96-2.75-2.417c0-1.19.911-2.049 2-2.323V10a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                            <div>
                                                <div class="fs-13 align-middle text-muted mb-1">Total Available Products</div>
                                                <div class="text-muted fs-12 mb-1">
                                                    <span class="text-dark fw-semibold fs-18">
                                                        <?php echo $totalProducts; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-success p-1 rounded-circle border border-success border-opacity-25 shadow-sm ms-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="p-2 bg-success bg-opacity-25 rounded-circle" width="40px" height="40px" viewBox="0 0 24 24"><path fill="currentColor" d="M4.083 10.894c.439-2.34.658-3.511 1.491-4.203C6.408 6 7.598 6 9.98 6h4.04c2.383 0 3.573 0 4.407.691c.833.692 1.052 1.862 1.491 4.203l.75 4c.617 3.292.926 4.938.026 6.022S18.12 22 14.771 22H9.23c-3.349 0-5.024 0-5.923-1.084c-.9-1.084-.591-2.73.026-6.022z" opacity="0.5"/><path fill="currentColor" d="M9.75 5a2.25 2.25 0 0 1 4.5 0v1c.566 0 1.062.002 1.5.015V5a3.75 3.75 0 1 0-7.5 0v1.015C8.688 6.002 9.184 6 9.75 6zm5.836 6.969a.75.75 0 1 0-1.172-.937l-3.476 4.345L9.53 13.97a.75.75 0 1 0-1.06 1.06l2 2a.75.75 0 0 0 1.116-.062z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                            <div>
                                                <div class="fs-13 align-middle text-muted mb-1">New Visitors</div>
                                                <div class="text-muted fs-12 mb-1">
                                                    <span class="text-dark fw-semibold fs-18">
                                                        <?php echo $newcustomer; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-info p-1 rounded-circle border border-info border-opacity-25 shadow-sm ms-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="p-2 bg-info bg-opacity-25 rounded-circle" width="40px" height="40px" viewBox="0 0 24 24"><path fill="currentColor" d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"/><path fill="currentColor" d="M14.477 21.92c-.726.053-1.547.08-2.477.08c-8 0-8-2.015-8-4.5S7.582 13 12 13c2.88 0 5.406.856 6.814 2.141C18.298 15 17.574 15 16.5 15c-1.65 0-2.475 0-2.987.513C13 16.025 13 16.85 13 18.5s0 2.475.513 2.987c.237.238.542.365.964.434" opacity="0.5"/><path fill="currentColor" fill-rule="evenodd" d="M16.5 22c-1.65 0-2.475 0-2.987-.513C13 20.975 13 20.15 13 18.5s0-2.475.513-2.987C14.025 15 14.85 15 16.5 15s2.475 0 2.987.513C20 16.025 20 16.85 20 18.5s0 2.475-.513 2.987C18.975 22 18.15 22 16.5 22m.583-5.056a.583.583 0 1 0-1.166 0v.973h-.973a.583.583 0 1 0 0 1.166h.973v.973a.583.583 0 1 0 1.166 0v-.973h.973a.583.583 0 1 0 0-1.166h-.973z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End::row-1 -->
            </div>
        </div>
        <!-- End::app-content -->
        <!-- END MAIN-CONTENT -->
<?php
include("footer.php");
?>