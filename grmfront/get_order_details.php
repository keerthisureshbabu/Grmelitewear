<?php
session_start();
include('../backend/pages/db.php');
include('../backend/libs/JWT.php');

// JWT secret key
define('JWT_SECRET', 'grm_shop_secret_key_2024');

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
$user = null;
if (isset($_COOKIE['auth_token'])) {
    try {
        $payload = JWT::decode($_COOKIE['auth_token'], JWT_SECRET);
        // Convert to object if it's an array
        $user = is_array($payload) ? (object)$payload : $payload;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Authentication failed']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Get order ID from request
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required']);
    exit();
}

try {
    // Get order details
    $stmt = $conn->prepare("
        SELECT o.*, c.name as customer_name, c.email, c.mob_number
        FROM orders o 
        JOIN customers c ON o.customer_id = c.id 
        WHERE o.id = ? AND o.customer_id = ?
    ");
    $stmt->bind_param("ii", $order_id, $user->user_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    
    if ($order_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found or access denied']);
        exit();
    }
    
    $order = $order_result->fetch_assoc();
    $stmt->close();
    
    // Get order items
    $stmt = $conn->prepare("
        SELECT oi.*, p.name as product_name, p.image, p.price as product_price
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items_result = $stmt->get_result();
    
    $order_items = [];
    while ($row = $items_result->fetch_assoc()) {
        $order_items[] = $row;
    }
    $stmt->close();
    
    // Generate HTML for order details
    $html = '
    <div class="order-details">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Order Information</h6>
                <p class="mb-1"><strong>Order #:</strong> ' . ($order['order_number'] ?? $order['id']) . '</p>
                <p class="mb-1"><strong>Date:</strong> ' . date('M d, Y H:i', strtotime($order['created_at'])) . '</p>
                <p class="mb-1"><strong>Status:</strong> 
                    <span class="badge bg-' . ($order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'info')) . '">
                        ' . ucfirst($order['status']) . '
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <h6>Customer Information</h6>
                <p class="mb-1"><strong>Name:</strong> ' . htmlspecialchars($order['customer_name']) . '</p>
                <p class="mb-1"><strong>Email:</strong> ' . htmlspecialchars($order['email']) . '</p>
                <p class="mb-1"><strong>Mobile:</strong> ' . htmlspecialchars($order['mob_number']) . '</p>
            </div>
        </div>
        
        <div class="mb-4">
            <h6>Order Items</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';
    
    foreach ($order_items as $item) {
        $html .= '
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="../backend/assets/images/products/' . htmlspecialchars($item['image']) . '" 
                                         alt="' . htmlspecialchars($item['product_name']) . '" 
                                         style="width: 50px; height: 50px; object-fit: cover;" class="me-3">
                                    <span>' . htmlspecialchars($item['product_name']) . '</span>
                                </div>
                            </td>
                            <td>₹' . number_format($item['product_price'], 2) . '</td>
                            <td>' . $item['quantity'] . '</td>
                            <td>₹' . number_format($item['total_price'], 2) . '</td>
                        </tr>';
    }
    
    $html .= '
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-end">
            <h5>Total Amount: ₹' . number_format($order['total_amount'], 2) . '</h5>
        </div>
    </div>';
    
    echo json_encode(['success' => true, 'html' => $html]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
