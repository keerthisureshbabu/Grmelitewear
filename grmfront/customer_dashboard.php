<?php
session_start();
include('../backend/pages/db.php');

// Check if user is logged in using session
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // User not logged in, redirect to login
    header("Location: account.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all user session variables
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_mobile']);
    unset($_SESSION['user_origin']);
    unset($_SESSION['redirect_after_login']);
    
    // Redirect to home page
    header("Location: index.php");
    exit();
}

// Get customer information
$customer_id = $user_id;
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer_result = $stmt->get_result();
$customer = $customer_result->fetch_assoc();
$stmt->close();

// Check if orders table exists and has the right structure
$table_check = $conn->query("SHOW TABLES LIKE 'orders'");
if ($table_check->num_rows > 0) {
    // Check if the orders table has the expected columns
    $columns_check = $conn->query("SHOW COLUMNS FROM orders LIKE 'customer_id'");
    if ($columns_check->num_rows > 0) {
        // Get order history - using a simpler query first
        $stmt = $conn->prepare("
            SELECT o.*, COUNT(oi.id) as item_count 
            FROM orders o 
            LEFT JOIN order_items oi ON o.id = oi.order_id 
            WHERE o.customer_id = ? 
            GROUP BY o.id 
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $orders_result = $stmt->get_result();
        $orders = [];
        while ($row = $orders_result->fetch_assoc()) {
            $orders[] = $row;
        }
        $stmt->close();
    } else {
        // If customer_id column doesn't exist, try alternative approach
        $orders = [];
        // You might want to check what columns actually exist
        $actual_columns = $conn->query("SHOW COLUMNS FROM orders");
        error_log("Orders table columns: " . print_r($actual_columns->fetch_all(), true));
    }
} else {
    // If orders table doesn't exist, create empty array
    $orders = [];
}

// Get recent order items for display
$recent_order_items = [];
if (!empty($orders)) {
    try {
        $first_order_id = $orders[0]['id'];
        
        // Check if order_items and products tables exist
        $oi_check = $conn->query("SHOW TABLES LIKE 'order_items'");
        $p_check = $conn->query("SHOW TABLES LIKE 'products'");
        
        if ($oi_check->num_rows > 0 && $p_check->num_rows > 0) {
            $stmt = $conn->prepare("
                SELECT oi.*, p.name as product_name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ? 
                LIMIT 5
            ");
            $stmt->bind_param("i", $first_order_id);
            $stmt->execute();
            $items_result = $stmt->get_result();
            while ($row = $items_result->fetch_assoc()) {
                $recent_order_items[] = $row;
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        // Log error but don't break the page
        error_log("Error fetching order items: " . $e->getMessage());
        $recent_order_items = [];
    }
}

// Debug information (remove this in production)
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;'>";
    echo "<h4>Debug Information:</h4>";
    echo "<p>Customer ID: " . $customer_id . "</p>";
    echo "<p>Orders count: " . count($orders) . "</p>";
    
    // Check what tables exist
    $tables = $conn->query("SHOW TABLES");
    echo "<p>Available tables:</p><ul>";
    while ($table = $tables->fetch_array()) {
        echo "<li>" . $table[0] . "</li>";
    }
    echo "</ul>";
    
    // Check orders table structure if it exists
    if ($conn->query("SHOW TABLES LIKE 'orders'")->num_rows > 0) {
        $columns = $conn->query("SHOW COLUMNS FROM orders");
        echo "<p>Orders table columns:</p><ul>";
        while ($column = $columns->fetch_assoc()) {
            echo "<li>" . $column['Field'] . " (" . $column['Type'] . ")</li>";
        }
        echo "</ul>";
    }
    
    echo "</div>";
}

include("header.php");
?>

<style>
.dashboard-container {
    min-height: 100vh;
    padding: 0;
}

.dashboard-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 2rem 0;
    margin-bottom: 3rem;
}

.dashboard-header h1 {
    color: #2d3748;
    font-weight: 700;
    margin: 0;
    font-size: 2.5rem;
}

.dashboard-header p {
    color: #718096;
    margin: 0.5rem 0 0 0;
    font-size: 1.1rem;
}

.stats-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    border-radius: 15px;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
}

.stat-icon.orders { background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); }
.stat-icon.completed { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
.stat-icon.processing { background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); }

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #718096;
    font-size: 1rem;
    font-weight: 500;
}

.content-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.content-card h3 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0.5rem;
}

.order-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.order-item:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-number {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
}

.order-status {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-completed { background: #c6f6d5; color: #22543d; }
.status-pending { background: #fef5e7; color: #744210; }
.status-processing { background: #bee3f8; color: #2a4365; }

.order-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    color: #718096;
}

.order-details span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3748;
    text-align: right;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.profile-item {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.profile-label {
    color: #718096;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.profile-value {
    color: #2d3748;
    font-weight: 600;
    font-size: 1.1rem;
}

.btn-modern {
    background: #ac5393;
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px #ac5393;
    color: white;
    text-decoration: none;
}

.btn-outline-modern {
    background: transparent;
    border: 2px solid #ac5393;
    color: #ac5393;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-outline-modern:hover {
    background: #ac5393;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px #ac5393;
    text-decoration: none;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #718096;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h4 {
    color: #4a5568;
    margin-bottom: 1rem;
}

.table-modern {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.table-modern th {
    background: #f7fafc;
    border: none;
    padding: 1.25rem 1rem;
    font-weight: 600;
    color: #2d3748;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.table-modern td {
    padding: 1.25rem 1rem;
    border: none;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background: #f8fafc;
}

.logout-btn {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(229, 62, 62, 0.4);
    color: white;
}

@media (max-width: 768px) {
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .stats-card {
        padding: 1.5rem;
    }
    
    .content-card {
        padding: 1.5rem;
    }
    
    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-amount {
        text-align: left;
    }
}
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1>Welcome back, <?= htmlspecialchars($user_name) ?>! 👋</h1>
                    <p>Here's what's happening with your account today</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="?logout=1" class="logout-btn">
                        <i class="ph ph-sign-out me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-card">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-icon orders">
                            <i class="ph ph-package"></i>
                        </div>
                        <div class="stat-number"><?= count($orders) ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-icon completed">
                            <i class="ph ph-check-circle"></i>
                        </div>
                        <div class="stat-number"><?= count(array_filter($orders, function($order) { return $order['status'] === 'completed'; })) ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-icon processing">
                            <i class="ph ph-clock"></i>
                        </div>
                        <div class="stat-number"><?= count(array_filter($orders, function($order) { return in_array($order['status'], ['pending', 'processing']); })) ?></div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Recent Orders -->
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Recent Orders</h3>
                        <a href="#all-orders" class="btn-outline-modern">View All Orders</a>
                    </div>
                    
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class="ph ph-package"></i>
                            <h4>No orders yet</h4> <br>
                            <p>Start shopping to see your order history here</p> <br>
                            <a href="index.php" class="btn-modern">Start Shopping</a>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                            <div class="order-item">
                                <div class="order-header">
                                    <div class="order-number">Order #<?= $order['order_number'] ?? $order['id'] ?></div>
                                    <span class="order-status status-<?= $order['status'] ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </div>
                                <div class="order-details">
                                    <span>
                                        <i class="ph ph-calendar"></i>
                                        <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                    </span>
                                    <span>
                                        <i class="ph ph-package"></i>
                                        <?= $order['item_count'] ?> item(s)
                                    </span>
                                </div>
                                <div class="order-amount">
                                    ₹<?= number_format($order['total_amount'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- All Orders Section -->
                <div id="all-orders" class="content-card">
                    <h3>All Orders</h3>
                    <?php if (empty($orders)): ?>
                        <p class="text-muted">No orders to display.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <strong><?= $order['order_number'] ?? $order['id'] ?></strong>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                            <td><?= $order['item_count'] ?> item(s)</td>
                                            <td><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                                            <td>
                                                <span class="order-status status-<?= $order['status'] ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn-outline-modern btn-sm" onclick="viewOrderDetails(<?= $order['id'] ?>)">
                                                    <i class="ph ph-eye me-1"></i>View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">

                <div class="content-card">
                    <h3>Profile Information</h3>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <div class="profile-label">Full Name</div>
                            <div class="profile-value"><?= htmlspecialchars($customer['name']) ?></div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-label">Email Address</div>
                            <div class="profile-value"><?= htmlspecialchars($customer['email']) ?></div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-label">Mobile Number</div>
                            <div class="profile-value"><?= htmlspecialchars($customer['mobile_num']) ?></div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-label">Member Since</div>
                            <div class="profile-value"><?= date('M d, Y', strtotime($customer['created_at'])) ?></div>
                        </div>
                    </div><br>
                    <div class="mt-4">
                        <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="ph ph-pencil me-2"></i>Edit Profile
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <h3>Quick Actions</h3>
                    <div class="d-grid gap-3">
                        <a href="index.php" class="btn-modern text-center">
                            <i class="ph ph-shopping-cart me-2"></i>Continue Shopping
                        </a>
                        <a href="cart.php" class="btn-outline-modern text-center">
                            <i class="ph ph-shopping-bag me-2"></i>View Cart
                        </a>
                        <a href="contact.php" class="btn-outline-modern text-center">
                            <i class="ph ph-headset me-2"></i>Get Support
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
                    </div>
                                            <div class="mb-3">
                            <label for="edit_mob_number" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control" id="edit_mob_number" name="mob_number" value="<?= htmlspecialchars($customer['mobile_num']) ?>" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modern">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Handle profile form submission
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'update_profile');
    
    fetch('update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully!');
            location.reload();
        } else {
            alert('Error updating profile: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating profile');
    });
});

// View order details
function viewOrderDetails(orderId) {
    fetch('get_order_details.php?order_id=' + orderId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('orderDetailsContent').innerHTML = data.html;
                new bootstrap.Modal(document.getElementById('orderDetailsModal')).show();
            } else {
                alert('Error loading order details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading order details');
        });
}

// Add active class to current navigation item
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// Add animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all content cards
document.querySelectorAll('.content-card, .stats-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
});
</script>

<?php include("footer.php"); ?>
