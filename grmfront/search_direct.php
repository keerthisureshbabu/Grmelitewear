<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once('../backend/pages/db.php');

// Initialize variables
$search_query = '';
$redirect_url = 'index.php'; // Default fallback

// Check if database connection is successful
if (isset($conn) && $conn && isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = trim($_GET['query']);
    
    // Validate search query length
    if (strlen($search_query) >= 2) {
        // Prepare the search query for case-insensitive partial matching
        $search_term = '%' . $search_query . '%';
        
        // Search in all category tables to find the best match
        $category_queries = [
            'kids' => 'SELECT id, name, "kids" as type FROM kids WHERE name LIKE ? ORDER BY name LIMIT 1',
            'women' => 'SELECT id, name, "women" as type FROM women WHERE name LIKE ? ORDER BY name LIMIT 1',
            'toy' => 'SELECT id, name, "toy" as type FROM toy WHERE name LIKE ? ORDER BY name LIMIT 1',
            'accessories' => 'SELECT id, name, "accessories" as type FROM accessories WHERE name LIKE ? ORDER BY name LIMIT 1'
        ];
        
        $best_match = null;
        $best_score = 0;
        
        foreach ($category_queries as $table => $sql) {
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $search_term);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    if ($result && $row = mysqli_fetch_assoc($result)) {
                        // Calculate a simple relevance score
                        $query_lower = strtolower($search_query);
                        $name_lower = strtolower($row['name']);
                        
                        // Exact match gets highest score
                        if ($query_lower === $name_lower) {
                            $score = 100;
                        }
                        // Starts with query gets high score
                        elseif (strpos($name_lower, $query_lower) === 0) {
                            $score = 80;
                        }
                        // Contains query gets medium score
                        elseif (strpos($name_lower, $query_lower) !== false) {
                            $score = 60;
                        }
                        // Partial match gets lower score
                        else {
                            $score = 40;
                        }
                        
                        if ($score > $best_score) {
                            $best_score = $score;
                            $best_match = $row;
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
        
        // If we found a match, redirect to the shop page
        if ($best_match && $best_score > 0) {
            $redirect_url = "shop.php?type=" . urlencode($best_match['type']) . "&cat=" . $best_match['id'];
        }
    }
}

// Redirect to the appropriate page
header("Location: " . $redirect_url);
exit();
?>
