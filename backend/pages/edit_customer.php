<?php
include 'db.php';
include("header.php");

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Invalid customer ID</div>';
    include("footer.php");
    exit;
}

$customer_id = (int)$_GET['id'];
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $origin = trim($_POST['origin']);
    
    if (empty($name) || empty($email) || empty($origin)) {
        $error_message = "Please fill in all fields";
    } elseif (!in_array($origin, ['direct', 'facebook', 'google'])) {
        $error_message = "Please select a valid origin";
    } else {
        // Check if email already exists for other customers
        $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "Email already registered by another customer";
        } else {
            // Update customer
            $stmt = $conn->prepare("UPDATE customers SET name = ?, email = ?, origin = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $origin, $customer_id);
            
            if ($stmt->execute()) {
                $success_message = "Customer updated successfully!";
            } else {
                $error_message = "Update failed. Please try again.";
            }
        }
        $stmt->close();
    }
}

// Get customer details
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-danger">Customer not found</div>';
    include("footer.php");
    exit;
}

$customer = $result->fetch_assoc();
$stmt->close();
?>

<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Edit Customer</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="enhanced_customer_management.php">Customer Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Messages -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i><?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i><?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Edit Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-edit me-2"></i>Edit Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($customer['name']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($customer['email']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="origin" class="form-label">How did they find us? *</label>
                                <select class="form-select" id="origin" name="origin" required>
                                    <option value="">Select an option</option>
                                    <option value="direct" <?= $customer['origin'] === 'direct' ? 'selected' : '' ?>>Directly to the website</option>
                                    <option value="facebook" <?= $customer['origin'] === 'facebook' ? 'selected' : '' ?>>Facebook</option>
                                    <option value="google" <?= $customer['origin'] === 'google' ? 'selected' : '' ?>>Google</option>
                                </select>
                                <div class="form-text">This helps track customer acquisition sources</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Registration Date</label>
                                <p class="form-control-plaintext">
                                    <?= date('F d, Y \a\t h:i A', strtotime($customer['created_at'])) ?>
                                </p>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-2"></i>Update Customer
                                </button>
                                <a href="enhanced_customer_management.php" class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-left me-2"></i>Back to List
                                </a>
                                <a href="customer_orders.php?customer_id=<?= $customer_id ?>" class="btn btn-outline-info">
                                    <i class="ti ti-shopping-cart me-2"></i>View Orders
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Customer Summary -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ti ti-info-circle me-2"></i>Customer Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Customer ID</label>
                            <p class="mb-0">#<?= $customer['id'] ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Current Origin</label>
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
                            <p class="mb-0"><?= date('M d, Y', strtotime($customer['created_at'])) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Days as Member</label>
                            <p class="mb-0">
                                <?php
                                $created = new DateTime($customer['created_at']);
                                $now = new DateTime();
                                $interval = $created->diff($now);
                                echo $interval->days . ' days';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ti ti-settings me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="customer_orders.php?customer_id=<?= $customer_id ?>" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-shopping-cart me-2"></i>View All Orders
                            </a>
                            <a href="enhanced_customer_management.php" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-users me-2"></i>Back to Customer List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

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

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control-plaintext {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    color: #495057;
    font-weight: 500;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.alert {
    border-radius: 12px;
    border: none;
    padding: 1rem 1.5rem;
}

.alert-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    color: white;
}

.alert-success {
    background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
    color: white;
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
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>

<?php include("footer.php"); ?>
