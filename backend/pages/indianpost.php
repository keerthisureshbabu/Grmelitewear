<?php 
// stcourier.php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/db.php';
include 'header.php';

/* --------------------------------------------
   HANDLE CRUD ACTIONS
-------------------------------------------- */
$errors = [];
$success = "";

// ADD
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $tracking_no = trim($_POST['tracking_no']);
    if ($tracking_no === '') {
        $errors[] = "Tracking number cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO indiapost_numbers (tracking_no) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $tracking_no);
            if ($stmt->execute()) {
                $success = "Tracking number added successfully!";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}


/* --------------------------------------------
   PAGINATION
-------------------------------------------- */
$limit = 10; // rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$totalRes = $conn->query("SELECT COUNT(*) AS total FROM indiapost_numbers");
$totalRow = $totalRes->fetch_assoc();
$totalRows = (int)$totalRow['total'];
$totalPages = (int)ceil($totalRows / $limit);

$result = $conn->query("SELECT * FROM indiapost_numbers ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>

<div class="main-content app-content">
    <h2 class="mb-4">Manage STCourier Tracking Numbers</h2>

    <!-- Messages -->
    <?php if ($errors): ?>
        <div class="alert alert-danger"><?= implode("<br>", $errors) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Add Form -->
    <div class="card mb-4">
        <div class="card-header">Add New Tracking Number</div>
        <div class="card-body">
            <form method="post" class="d-flex gap-2">
                <input type="hidden" name="action" value="add">
                <input type="text" name="tracking_no" class="form-control" placeholder="Enter tracking number" required>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-fixed">
            <thead class="table-dark">
                <tr>
                    <th style="width:5%">ID</th>
                    <th style="width:60%">Tracking Number</th>
                    <th style="width:15%">Used?</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="post">
                                <td><?= $row['id'] ?></td>
                                <td>
                                    <input type="text" name="tracking_no" value="<?= htmlspecialchars($row['tracking_no']) ?>" class="form-control">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="is_used" <?= $row['is_used'] ? 'checked' : '' ?>>
                                </td>
                              
                            </form>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No tracking numbers available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
     <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
              <a class="page-link" href="indianpost.php?page=<?= $i; ?>"
                 style="background-color:#1e1e1e; color:#fff; border:1px solid #333;"><?= $i; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>

</div>

</body>
</html>
<?php include 'footer.php'; ?>
