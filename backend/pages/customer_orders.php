<?php
include 'db.php';
// include("header.php");

// Check if customer ID is provided
if (!isset($_GET['customer_id']) || !is_numeric($_GET['customer_id'])) {
    echo '<div class="alert alert-danger">Invalid customer ID</div>';
    include("footer.php");
    exit;
}

$customer_id = (int)$_GET['customer_id'];

// Get customer details
$customer_sql = "SELECT * FROM customers WHERE id = ?";
$customer_stmt = $conn->prepare($customer_sql);
$customer_stmt->bind_param("i", $customer_id);
$customer_stmt->execute();
$customer_result = $customer_stmt->get_result();

if ($customer_result->num_rows === 0) {
    echo '<div class="alert alert-danger">Customer not found</div>';
    include("footer.php");
    exit;
}

$customer = $customer_result->fetch_assoc();

// Get customer's orders
$orders_sql = "
SELECT 
    o.*,
    COUNT(oi.id) as item_count,
    GROUP_CONCAT(oi.product_name SEPARATOR ', ') as product_names
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
WHERE o.user_id = ?
GROUP BY o.id
ORDER BY o.order_date DESC
";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->bind_param("i", $customer_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);

// Calculate totals
$total_orders = count($orders);
$total_spent = array_sum(array_column($orders, 'total_amount'));
$total_shipping = array_sum(array_column($orders, 'shipping_cost'));
$grand_total = $total_spent + $total_shipping;
?>

<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Customer Orders</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="enhanced_customer_management.php">Customer Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Customer Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Customer ID</label>
                                    <p class="mb-0">#<?= $customer['id'] ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Full Name</label>
                                    <p class="mb-0"><?= htmlspecialchars($customer['name']) ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Email Address</label>
                                    <p class="mb-0"><?= htmlspecialchars($customer['email']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Origin</label>
                                    <p class="mb-0">
                                        <?php
                                        $origin_labels = [
                                            'direct' => 'Directly to the website',
                                            'facebook' => 'Facebook',
                                            'google' => 'Google'
                                        ];
                                        $origin_icons = [
                                            'direct' => 'ti-world',
                                            'facebook' => 'ti-brand-facebook',
                                            'google' => 'ti-brand-google'
                                        ];
                                        $origin_colors = [
                                            'direct' => 'text-primary',
                                            'facebook' => 'text-primary',
                                            'google' => 'text-danger'
                                        ];
                                        ?>
                                        <span class="<?= $origin_colors[$customer['origin']] ?>">
                                            <i class="<?= $origin_icons[$customer['origin']] ?> me-2"></i>
                                            <?= $origin_labels[$customer['origin']] ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Member Since</label>
                                    <p class="mb-0"><?= date('F d, Y', strtotime($customer['created_at'])) ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Registration Time</label>
                                    <p class="mb-0"><?= date('h:i A', strtotime($customer['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card custom-card border border-primary border-opacity-10 bg-primary-transparent">
                    <div class="card-body text-center">
                        <h4 class="mb-1 text-primary"><?= $total_orders ?></h4>
                        <p class="mb-0 text-muted">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card custom-card border border-success border-opacity-10 bg-success-transparent">
                    <div class="card-body text-center">
                        <h4 class="mb-1 text-success">₹<?= number_format($total_spent, 2) ?></h4>
                        <p class="mb-0 text-muted">Total Spent</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card custom-card border border-info border-opacity-10 bg-info-transparent">
                    <div class="card-body text-center">
                        <h4 class="mb-1 text-info">₹<?= number_format($total_shipping, 2) ?></h4>
                        <p class="mb-0 text-muted">Total Shipping</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card custom-card border border-warning border-opacity-10 bg-warning-transparent">
                    <div class="card-body text-center">
                        <h4 class="mb-1 text-warning">₹<?= number_format($grand_total, 2) ?></h4>
                        <p class="mb-0 text-muted">Grand Total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="ti ti-shopping-cart me-2"></i>Order History
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="exportOrdersToCSV()">
                                <i class="ti ti-download me-1"></i>Export CSV
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="printOrders()">
                                <i class="ti ti-printer me-1"></i>Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="ti ti-shopping-cart text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">No orders found</h5>
                                <p class="text-muted mb-4">This customer hasn't placed any orders yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap align-middle" id="ordersTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Subtotal</th>
                                            <th>Shipping</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Tracking</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">#<?= $order['id'] ?></span>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <?= date('M d, Y', strtotime($order['order_date'])) ?>
                                                    </div>
                                                    <div class="text-muted smaller">
                                                        <?= date('h:i A', strtotime($order['order_date'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small fw-semibold">
                                                        <?= $order['item_count'] ?> item<?= $order['item_count'] > 1 ? 's' : '' ?>
                                                    </div>
                                                    <div class="text-muted smaller text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($order['product_names']) ?>">
                                                        <?= htmlspecialchars($order['product_names']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">₹<?= number_format($order['total_amount'], 2) ?></div>
                                                </td>
                                                <td>
                                                    <div class="text-muted">₹<?= number_format($order['shipping_cost'], 2) ?></div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">₹<?= number_format($order['total_amount'] + $order['shipping_cost'], 2) ?></div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status_class = '';
                                                    $status_icon = '';
                                                    switch ($order['status']) {
                                                        case 'pending':
                                                            $status_class = 'bg-warning';
                                                            $status_icon = 'ti-clock';
                                                            break;
                                                        case 'processing':
                                                            $status_class = 'bg-info';
                                                            $status_icon = 'ti-settings';
                                                            break;
                                                        case 'shipped':
                                                            $status_class = 'bg-primary';
                                                            $status_icon = 'ti-truck';
                                                            break;
                                                        case 'delivered':
                                                            $status_class = 'bg-success';
                                                            $status_icon = 'ti-check';
                                                            break;
                                                        case 'cancelled':
                                                            $status_class = 'bg-danger';
                                                            $status_icon = 'ti-x';
                                                            break;
                                                        default:
                                                            $status_class = 'bg-secondary';
                                                            $status_icon = 'ti-help';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $status_class ?> d-flex align-items-center gap-1" style="width: fit-content;">
                                                        <i class="<?= $status_icon ?>"></i>
                                                        <?= ucfirst($order['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="font-monospace small">
                                                        <?= htmlspecialchars($order['tracking_number']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="../grmfront/order_success.php?order_id=<?= $order['id'] ?>" 
                                                           class="btn btn-outline-primary" 
                                                           title="View Details" target="_blank">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="../grmfront/trackorder.php?tracking_number=<?= urlencode($order['tracking_number']) ?>" 
                                                           class="btn btn-outline-info" 
                                                           title="Track Order" target="_blank">
                                                            <i class="ti ti-truck"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mt-4">
            <div class="col-12">
                <a href="enhanced_customer_management.php" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Back to Customer Management
                </a>
            </div>
        </div>

    </div>
</div>

<script>
function exportOrdersToCSV() {
    const table = document.getElementById('ordersTable');
    if (!table) return;
    
    const rows = Array.from(table.querySelectorAll('tr'));
    
    let csv = [];
    rows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('td, th'));
        const rowData = cols.map(col => {
            const text = col.textContent || col.innerText || '';
            return `"${text.replace(/"/g, '""')}"`;
        });
        csv.push(rowData.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'customer_orders_<?= $customer['id'] ?>.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function printOrders() {
    window.print();
}
</script>

<style>
.bg-main {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-main {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    color: white;
}

.card {
    transition: transform 0.2s ease-in-out;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.card-header {
    border-bottom: none;
    padding: 1.5rem 1.5rem 1rem;
    border-radius: 12px 12px 0 0;
}

.card-body {
    padding: 1.5rem;
}

.breadcrumb {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 1rem 1.5rem;
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-item a:hover {
    color: #764ba2;
}

.breadcrumb-item.active {
    color: #6c757d;
}

.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-outline-info {
    border-color: #17a2b8;
    color: #17a2b8;
}

.btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.font-monospace {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.smaller {
    font-size: 0.75rem;
}

.bg-primary-transparent {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.bg-success-transparent {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.1) 100%);
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.bg-warning-transparent {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.1) 100%);
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.bg-info-transparent {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(23, 162, 184, 0.1) 100%);
    border: 1px solid rgba(23, 162, 184, 0.2);
}

/* Enhanced table styling */
.table {
    border-radius: 8px;
    overflow: hidden;
}

.table-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 6px;
}

/* Statistics cards styling */
.text-primary {
    color: #667eea !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-success {
    color: #28a745 !important;
}

/* Print styles */
@media print {
    .main-content .container-fluid > *:not(.card) {
        display: none !important;
    }
    .card-header, .card-footer {
        display: none !important;
    }
    .btn-group {
        display: none !important;
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .breadcrumb {
        padding: 0.75rem 1rem;
    }
    
    .btn {
        padding: 0.625rem 1.25rem;
    }
    
    .table-responsive {
        border-radius: 8px;
    }
}
</style>

<?php 
$customer_stmt->close();
$orders_stmt->close();
include("footer.php"); 
?>
