<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once('../backend/pages/db.php');

// Set content type to JSON
header('Content-Type: application/json');

// Initialize variables
$search_results = [];
$error_message = '';

// Check if database connection is successful
if (!isset($conn) || !$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Process search query
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = trim($_GET['query']);
    
    // Validate search query length
    if (strlen($search_query) < 2) {
        echo json_encode(['error' => 'Search query must be at least 2 characters long']);
        exit;
    }
    
    // Prepare the search query for case-insensitive partial matching
    $search_term = '%' . $search_query . '%';
    
    // Search in all category tables
    $category_queries = [
        'kids' => 'SELECT id, name, "kids" as type FROM kids WHERE name LIKE ? ORDER BY name LIMIT 5',
        'women' => 'SELECT id, name, "women" as type FROM women WHERE name LIKE ? ORDER BY name LIMIT 5',
        'toy' => 'SELECT id, name, "toy" as type FROM toy WHERE name LIKE ? ORDER BY name LIMIT 5',
        'accessories' => 'SELECT id, name, "accessories" as type FROM accessories WHERE name LIKE ? ORDER BY name LIMIT 5'
    ];
    
    foreach ($category_queries as $table => $sql) {
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $search_term);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $search_results[] = $row;
                    }
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Database query error occurred";
            break;
        }
    }
    
    // Return results or error
    if (!empty($error_message)) {
        echo json_encode(['error' => $error_message]);
    } else {
        echo json_encode($search_results);
    }
} else {
    echo json_encode(['error' => 'No search query provided']);
}
?>

