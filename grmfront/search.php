<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once('../backend/pages/db.php');

// Initialize variables
$search_results = [];
$search_query = '';
$error_message = '';

// Check if database connection is successful
if (!isset($conn) || !$conn) {
    $error_message = "Database connection failed. Please try again later.";
} else {
    // Process search query
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $search_query = trim($_GET['query']);
        
        // Validate search query length
        if (strlen($search_query) < 2) {
            $error_message = "Search query must be at least 2 characters long.";
        } else {
            // Prepare the search query for case-insensitive partial matching
            $search_term = '%' . $search_query . '%';
            
            // Search in all category tables
            $category_queries = [
                'kids' => 'SELECT id, name, "kids" as type FROM kids WHERE name LIKE ? ORDER BY name',
                'women' => 'SELECT id, name, "women" as type FROM women WHERE name LIKE ? ORDER BY name',
                'toy' => 'SELECT id, name, "toy" as type FROM toy WHERE name LIKE ? ORDER BY name',
                'accessories' => 'SELECT id, name, "accessories" as type FROM accessories WHERE name LIKE ? ORDER BY name'
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
                    $error_message = "Database query error occurred.";
                    break;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Grm Elite Wear</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .search-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .search-form {
            margin-bottom: 30px;
        }
        .search-input {
            border-radius: 25px;
            padding: 15px 20px;
            font-size: 16px;
        }
        .search-button {
            border-radius: 25px;
            padding: 15px 30px;
            background-color: #007bff;
            border: none;
            color: white;
        }
        .search-button:hover {
            background-color: #0056b3;
        }
        .result-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .result-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .result-link {
            text-decoration: none;
            color: #333;
            display: block;
        }
        .result-link:hover {
            color: #007bff;
        }
        .category-badge {
            background: #007bff;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            text-transform: capitalize;
        }
        .no-results {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .results-count {
            color: #666;
            margin-bottom: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="search-container">
        <div class="search-form">
            <form method="GET" action="search.php" class="d-flex gap-3">
                <input type="text" 
                       name="query" 
                       class="form-control search-input" 
                       placeholder="Search for products, categories or brands..." 
                       value="<?php echo htmlspecialchars($search_query); ?>"
                       minlength="2"
                       required>
                <button type="submit" class="btn search-button">
                    <i class="ph ph-magnifying-glass"></i> Search
                </button>
            </form>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($search_query) && empty($error_message)): ?>
            <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
            
            <?php if (empty($search_results)): ?>
                <div class="no-results">
                    <h4>No results found</h4>
                    <p>Try searching with different keywords or browse our categories.</p>
                    <a href="index.php" class="back-link">← Back to Home</a>
                </div>
            <?php else: ?>
                <div class="results-count">
                    Found <?php echo count($search_results); ?> result<?php echo count($search_results) !== 1 ? 's' : ''; ?>
                </div>
                
                <div class="results-list">
                    <?php foreach ($search_results as $result): ?>
                        <div class="result-item">
                            <a href="shop.php?type=<?php echo urlencode($result['type']); ?>&cat=<?php echo htmlspecialchars($result['id']); ?>" 
                               class="result-link">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1"><?php echo htmlspecialchars($result['name']); ?></h5>
                                        <span class="category-badge"><?php echo htmlspecialchars($result['type']); ?></span>
                                    </div>
                                    <i class="ph ph-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="back-link">← Back to Home</a>
                </div>
            <?php endif; ?>
        <?php elseif (empty($search_query) && empty($error_message)): ?>
            <div class="text-center">
                <h3>Search Products</h3>
                <p>Enter a search term above to find products and categories.</p>
                <a href="index.php" class="back-link">← Back to Home</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'footer.php'; ?>