<?php
// update_womens_product.php
// Expects ?code=PRODUCT_CODE
// Path: backend/pages/update_womens_product.php

// load DB connection (adjust path only if your db.php is elsewhere)
include __DIR__ . '/db.php';

if (!isset($_GET['code']) || trim($_GET['code']) === '') {
    die("Invalid request. No product code provided.");
}
$product_code = trim($_GET['code']);

// ---------------------- Fetch product and variations ----------------------
$sql = "
    SELECT p.*, c.name AS category_name, c.id AS category_id
    FROM womens_products p
    LEFT JOIN women c ON p.variation_id = c.id
    WHERE p.product_code = ?
    ORDER BY p.id
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $product_code);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

if (!$res || mysqli_num_rows($res) == 0) {
    die("Product not found for code: " . htmlspecialchars($product_code));
}

// group product data
$product = [
    'id' => '',
    'product_name' => '',
    'mrp_price' => '',
    'actual_price' => '',
    'images' => ['', '', '', ''],
    'variations' => []
];

while ($row = mysqli_fetch_assoc($res)) {
    $product['id'] = $row['variation_id'];
    $product['product_name'] = $row['product_name'];
    $product['mrp_price'] = $row['mrp_price'];
    $product['actual_price'] = $row['actual_price'];
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

// fetch categories for select
$catsRes = mysqli_query($conn, "SELECT id, name FROM women ORDER BY name");

// ---------------------- Handle POST (update) ----------------------
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // posted values (sanitize minimally here; prepared statements used below)
    $variation_id = $_POST['id'] ?? '';
    $product_name = trim($_POST['product_name'] ?? '');
    $mrp_price = isset($_POST['mrp_price']) ? (float)$_POST['mrp_price'] : 0.0;
    $actual_price = isset($_POST['actual_price']) ? (float)$_POST['actual_price'] : 0.0;

    // validations
    if ($variation_id === '' || !is_numeric($variation_id)) $errors[] = "Please select a category.";
    if ($product_name === '') $errors[] = "Product name is required.";
    if ($mrp_price <= 0) $errors[] = "Please enter a valid MRP.";
    if ($actual_price < 0) $errors[] = "Please enter a valid Actual price.";
    if ($actual_price > $mrp_price) $errors[] = "Actual price cannot be greater than MRP.";

    // images: prepare upload dir
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    // start with existing images
    $images = $product['images'];

    // handle image uploads (1..4). if uploaded, replace; otherwise keep existing hidden input values
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($_FILES["image{$i}"]["name"])) {
            $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES["image{$i}"]["name"]));
            $target = $upload_dir . time() . "_w" . $i . "_" . $safeName;
            if (move_uploaded_file($_FILES["image{$i}"]["tmp_name"], $target)) {
                // store relative path for serving (adjust if your public path differs)
                $images[$i - 1] = 'uploads/' . basename($target);
            } else {
                $errors[] = "Failed to upload Image {$i}.";
            }
        } else {
            // use hidden existing input if provided (keeps same value)
            $existing = $_POST["existing_image{$i}"] ?? '';
            if ($existing !== '') $images[$i - 1] = $existing;
        }
    }

    // variation arrays
    $variation_codes = $_POST['variation_code'] ?? [];
    $attributes = $_POST['attribute'] ?? [];
    $inventory_counts = $_POST['inventory_count'] ?? [];

    if (empty($variation_codes) || !is_array($variation_codes)) {
        $errors[] = "Please provide at least one variation.";
    }

    // if no validation errors, perform DB update inside transaction
    if (empty($errors)) {
        mysqli_begin_transaction($conn);
        try {
            // delete existing rows for this product_code
            $delStmt = mysqli_prepare($conn, "DELETE FROM womens_products WHERE product_code = ?");
            mysqli_stmt_bind_param($delStmt, "s", $product_code);
            mysqli_stmt_execute($delStmt);
            mysqli_stmt_close($delStmt);

            // prepare insert
            $insSql = "
                INSERT INTO womens_products
                (variation_id, variation_name, product_code, variation_code, product_name, image1, image2, image3, image4, attribute, inventory_count, mrp_price, actual_price)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $insStmt = mysqli_prepare($conn, $insSql);
            if (!$insStmt) throw new Exception("Prepare failed: " . mysqli_error($conn));

            // fetch category name (denormalized) to store as variation_name
            $catStmt = mysqli_prepare($conn, "SELECT name FROM women WHERE id = ?");
            mysqli_stmt_bind_param($catStmt, "i", $variation_id);
            mysqli_stmt_execute($catStmt);
            mysqli_stmt_bind_result($catStmt, $variation_name);
            if (!mysqli_stmt_fetch($catStmt)) {
                mysqli_stmt_close($catStmt);
                throw new Exception("Selected category not found.");
            }
            mysqli_stmt_close($catStmt);

            $inserted = 0;
            foreach ($variation_codes as $idx => $rawCode) {
                $vcode = trim($rawCode);
                if ($vcode === '') continue;

                $attr = trim($attributes[$idx] ?? '');
                $inv = (int)($inventory_counts[$idx] ?? 0);

                // ensure unique variation_code (append suffix if collision)
                $attempt = $vcode;
                $k = 1;
                while (true) {
                    $chkStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS cnt FROM womens_products WHERE variation_code = ?");
                    mysqli_stmt_bind_param($chkStmt, "s", $attempt);
                    mysqli_stmt_execute($chkStmt);
                    $resChk = mysqli_stmt_get_result($chkStmt);
                    $rowChk = mysqli_fetch_assoc($resChk);
                    mysqli_stmt_close($chkStmt);
                    if ($rowChk['cnt'] == 0) break;
                    $attempt = $vcode . '-' . $k;
                    $k++;
                }
                $final_vcode = $attempt;

                // ---- CORRECT bind_param: 13 types for 13 variables ----
                // types: i (variation_id),
                //        s (variation_name),
                //        s (product_code),
                //        s (variation_code),
                //        s (product_name),
                //        s (image1),
                //        s (image2),
                //        s (image3),
                //        s (image4),
                //        s (attribute),
                //        i (inventory_count),
                //        d (mrp_price),
                //        d (actual_price)
                mysqli_stmt_bind_param(
                    $insStmt,
                    "isssssssssidd",
                    $variation_id,
                    $variation_name,
                    $product_code,
                    $final_vcode,
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
            mysqli_commit($conn);

            // redirect to listing with success flag
            header("Location: view_womens_products.php?updated=1");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// include header/footer (adjust path if needed)
include __DIR__ . '/header.php';
?>

<div class="main-content app-content" style="min-height:100vh; background:#2a2a2c; color:#fff;">
  <div class="container-fluid py-4">
    <h4 class="mb-3"><b>Update Women's Product</b></h4>

    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $err): ?>
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
              mysqli_data_seek($catsRes, 0);
              while ($c = mysqli_fetch_assoc($catsRes)):
            ?>
              <option value="<?= (int)$c['id']; ?>" <?= ((int)$c['id'] === (int)$product['id']) ? 'selected' : ''; ?>>
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
        <a href="view_womens_products.php" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const addBtn = document.getElementById('addVariation');
  const tableBody = document.getElementById('variationsTable').querySelector('tbody');

  addBtn.addEventListener('click', function() {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" name="variation_code[]" class="form-control" required></td>
      <td><input type="text" name="attribute[]" class="form-control"></td>
      <td><input type="number" name="inventory_count[]" class="form-control"></td>
      <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
    `;
    tableBody.appendChild(newRow);
  });

  tableBody.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
      e.target.closest('tr').remove();
    }
  });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
