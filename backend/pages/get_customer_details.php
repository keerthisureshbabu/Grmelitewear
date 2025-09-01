<?php
include 'db.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid customer ID']);
    exit;
}

$customer_id = (int)$_GET['id'];

try {
    // Get customer details with order statistics
    $sql = "
    SELECT 
        c.id,
        c.name,
        c.email,
        c.origin,
        c.created_at,
        COUNT(o.id) as total_orders,
        COALESCE(SUM(o.total_amount), 0) as total_spent
    FROM customers c
    LEFT JOIN orders o ON c.id = o.user_id
    WHERE c.id = ?
    GROUP BY c.id
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Customer not found']);
        exit;
    }
    
    $customer = $result->fetch_assoc();
    
    // Format dates and numbers
    $customer['created_at'] = date('M d, Y h:i A', strtotime($customer['created_at']));
    $customer['total_spent'] = number_format($customer['total_spent'], 2);
    $customer['origin'] = ucfirst($customer['origin']);
    
    echo json_encode($customer);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
