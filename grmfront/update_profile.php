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

// Handle profile update
if ($_POST['action'] === 'update_profile') {
    $name = trim($_POST['name']);
    $mob_number = trim($_POST['mob_number']);
    
    if (empty($name) || empty($mob_number)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }
    
    // Validate mobile number (basic validation)
    if (!preg_match('/^[0-9]{10}$/', $mob_number)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid 10-digit mobile number']);
        exit();
    }
    
    try {
        // Update customer profile
        $stmt = $conn->prepare("UPDATE customers SET name = ?, mob_number = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $mob_number, $user->user_id);
        
        if ($stmt->execute()) {
            // Update JWT token with new information
            $payload = [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'name' => $name,
                'mob_number' => $mob_number,
                'iat' => time(),
                'exp' => time() + (24 * 60 * 60) // 24 hours
            ];
            
            $token = JWT::encode($payload, JWT_SECRET);
            
            // Update the auth cookie
            setcookie('auth_token', $token, time() + (24 * 60 * 60), '/', '', true, true);
            
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
