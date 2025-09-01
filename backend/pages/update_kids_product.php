<?php
include 'db.php';

// ------------------ GET PRODUCT BY CODE ------------------
if (!isset($_GET['code']) || trim($_GET['code']) === '') {
    die("Invalid request. No product code.");
}
$product_code = trim($_GET['code']);

// Fetch product info and variations using prepared statement
$sql = "
    SELECT p.*, k.name AS category_name, k.id AS category_id
    FROM kids_products p
    LEFT JOIN kids k ON p.variation_id = k.id
    WHERE p.product_code = ?
    ORDER BY p.id
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $product_code);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) == 0) {
    die("Product not found.");
}

// Group product info
$product = [
    'id' => '',
    'product_name' => '',
    'mrp_price' => '',
    'actual_price' => '',
    'images' => ['', '', '', ''],
    'variations' => []
];

while ($row = mysqli_fetch_assoc($res)) {
    // fill main product info (same across variation rows)
    $product['id'] = $row['variation_id']; // category id
    $product['product_name'] = $row['product_name'];
    $product['mrp_price'] = $row['mrp_price'];
    $product['actual_price'] = $row['actual_price'];
    // prefer first non-empty image if duplicates exist
    $product['images'] = [
        $row['image1'] ?? '',
        $row['image2'] ?? '',
        $row['image3'] ?? '',
        $row['image4'] ?? ''
    ];
    $product['variations'][] = [
        'variation_code' => $row['variation_code'],
        'attribute' => $row['attribute'],
        'inventory_count' => $row['inventory_count']
    ];
}
mysqli_stmt_close($stmt);

// Fetch categories
$cats = mysqli_query($conn, "SELECT id, name FROM kids ORDER BY name");

// ------------------ HANDLE UPDATE ------------------
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect posted values
    $variation_id = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name'] ?? '');
    $mrp_price = (float)($_POST['mrp_price'] ?? 0);
    $actual_price = (float)($_POST['actual_price'] ?? 0);

    // Basic validations
    if ($variation_id === '' || !is_numeric($variation_id)) $errors[] = "Please select a valid category.";
    if ($product_name === '') $errors[] = "Product name is required.";
    if ($mrp_price <= 0) $errors[] = "Please enter a valid MRP.";
    if ($actual_price < 0) $errors[] = "Please enter a valid Actual price.";
    if ($actual_price > $mrp_price) $errors[] = "Actual price cannot be greater than MRP.";

    // Image upload helper (sanitizes filename)
    $upload_dir = __DIR__ . "/uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $images = $product['images']; // default to existing images
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($_FILES["image{$i}"]["name"])) {
            // sanitize filename
            $name = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES["image{$i}"]["name"]));
            $target = $upload_dir . time() . "_" . $i . "_" . $name;
            if (move_uploaded_file($_FILES["image{$i}"]["tmp_name"], $target)) {
                // store web-accessible path relative to project (adjust if needed)
                $images[$i - 1] = "uploads/" . basename($target);
            } else {
                $errors[] = "Failed to upload Image {$i}.";
            }
        } else {
            // keep existing image value from hidden input (if user provided)
            $existing = $_POST["existing_image{$i}"] ?? '';
            if ($existing !== '') $images[$i - 1] = $existing;
        }
    }

    // Variation inputs validation
    $variation_codes = $_POST['variation_code'] ?? [];
    $attributes = $_POST['attribute'] ?? [];
    $inventory_counts = $_POST['inventory_count'] ?? [];

    if (empty($variation_codes) || !is_array($variation_codes)) {
        $errors[] = "Please provide at least one variation.";
    }

    // If no errors so far, perform DB update in transaction
    if (empty($errors)) {
        // begin transaction
        mysqli_begin_transaction($conn);

        try {
            // 1) Delete old rows for this product_code
            $delStmt = mysqli_prepare($conn, "DELETE FROM kids_products WHERE product_code = ?");
            mysqli_stmt_bind_param($delStmt, "s", $product_code);
            mysqli_stmt_execute($delStmt);
            mysqli_stmt_close($delStmt);

            // 2) Re-insert variations using prepared statement
            $insSql = "
                INSERT INTO kids_products
                (variation_id, product_code, variation_code, product_name, image1, image2, image3, image4, attribute, inventory_count, mrp_price, actual_price)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $insStmt = mysqli_prepare($conn, $insSql);
            if (!$insStmt) throw new Exception("Prepare failed: " . mysqli_error($conn));

            $inserted = 0;
            foreach ($variation_codes as $idx => $vcodeRaw) {
                $vcode = trim($vcodeRaw);
                if ($vcode === '') continue; // skip empty codes

                $attr = trim($attributes[$idx] ?? '');
                $inv = (int)($inventory_counts[$idx] ?? 0);

                // ensure uniqueness of variation_code in practice (append suffix if collision)
                $attemptCode = $vcode;
                $k = 1;
                while (true) {
                    $checkStmt = mysqli_prepare($conn, "SELECT COUNT(*) as cnt FROM kids_products WHERE variation_code = ?");
                    mysqli_stmt_bind_param($checkStmt, "s", $attemptCode);
                    mysqli_stmt_execute($checkStmt);
                    $resChk = mysqli_stmt_get_result($checkStmt);
                    $rowChk = mysqli_fetch_assoc($resChk);
                    mysqli_stmt_close($checkStmt);
                    if ($rowChk['cnt'] == 0) break;
                    $attemptCode = $vcode . '-' . $k;
                    $k++;
                }
                $variation_code_final = $attemptCode;

                // bind and execute insert
                mysqli_stmt_bind_param(
                    $insStmt,
                    "issssssssidd",
                    $variation_id,
                    $product_code,
                    $variation_code_final,
                    $product_name,
                    $images[0],
                    $images[1],
                    $images[2],
                    $images[3],
                    $attr,
                    $inv,
                    $mrp_price,
                    $actual_price
                );

                mysqli_stmt_execute($insStmt);
                $inserted++;
            }

            mysqli_stmt_close($insStmt);

            // commit transaction
            mysqli_commit($conn);
            $success = true;

            // redirect back to listing or show success
            header("Location: view_kids_products.php?updated=1");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} // end POST

// include header AFTER server-side redirect logic above
include("header.php");
?>

<div class="main-content app-content" style="background-color:rgb(42, 42, 44); min-height:100vh; color:#fff;">
  <div class="container-fluid py-4">
    <h4 class="mb-3"><b>Update Kids Product</b></h4>

    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $err): ?>0
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="id" class="form-select" required>
            <option value="">-- Select Category --</option>
            <?php
              // rewind categories result pointer (if needed)
              mysqli_data_seek($cats, 0);
              while ($c = mysqli_fetch_assoc($cats)):
            ?>
              <option value="<?= (int)$c['id']; ?>" <?= ((int)$c['id'] == (int)$product['id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($c['name']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']); ?>" required>
        </div>

        <div class="row">
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="col-md-3 mb-3">
              <label class="form-label">Image <?= $i; ?></label><br>
              <?php if (!empty($product['images'][$i-1])): ?>
                <img src="<?= htmlspecialchars($product['images'][$i-1]); ?>" style="width:100px;height:100px;object-fit:cover;" class="mb-2"><br>
              <?php endif; ?>
              <input type="file" name="image<?= $i; ?>" class="form-control">
              <input type="hidden" name="existing_image<?= $i; ?>" value="<?= htmlspecialchars($product['images'][$i-1]); ?>">
            </div>
          <?php endfor; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">MRP Price</label>
          <input type="number" step="0.01" name="mrp_price" class="form-control" value="<?= htmlspecialchars($product['mrp_price']); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Actual Price</label>
          <input type="number" step="0.01" name="actual_price" class="form-control" value="<?= htmlspecialchars($product['actual_price']); ?>" required>
        </div>

        <hr>
        <h5>Variations</h5>
        <div class="table-responsive mb-3">
          <table class="table table-bordered" id="variationsTable">
            <thead class="table-light">
              <tr>
                <th>Variation Code</th>
                <th>Attribute</th>
                <th>Inventory Count</th>
                <th style="width:50px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($product['variations'] as $v): ?>
                <tr>
                  <td><input type="text" name="variation_code[]" value="<?= htmlspecialchars($v['variation_code']); ?>" class="form-control" required></td>
                  <td><input type="text" name="attribute[]" value="<?= htmlspecialchars($v['attribute']); ?>" class="form-control"></td>
                  <td><input type="number" name="inventory_count[]" value="<?= htmlspecialchars($v['inventory_count']); ?>" class="form-control"></td>
                  <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <button type="button" class="btn btn-info mb-3" id="addVariation">Add More Variation</button>
        <br>
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="view_kids_products.php" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const addBtn = document.getElementById('addVariation');
  const table = document.getElementById('variationsTable').querySelector('tbody');

  addBtn.addEventListener('click', function() {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" name="variation_code[]" class="form-control" required></td>
      <td><input type="text" name="attribute[]" class="form-control"></td>
      <td><input type="number" name="inventory_count[]" class="form-control"></td>
      <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
    `;
    table.appendChild(newRow);
  });

  table.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
      e.target.closest('tr').remove();
    }
  });
});
</script>

<?php include("footer.php"); ?>
