<?php
// update_accessories_product.php
include 'db.php';

// ------------------ VALIDATION ------------------
if (!isset($_GET['product_code']) || trim($_GET['product_code']) === '') {
    die("Invalid request. No product code.");
}
$product_code = trim($_GET['product_code']); // correct key

// ----------- helpers -----------
function uploadImageFile($fileField, $upload_dir = "uploads/") {
    if (!isset($_FILES[$fileField])) return '';
    if (empty($_FILES[$fileField]['name'])) return '';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $safe = preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES[$fileField]['name']));
    $filename = time() . '_' . mt_rand(1000,9999) . '_' . $safe;
    $target = rtrim($upload_dir, '/') . '/' . $filename;
    if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $target)) return $target;
    return '';
}

/**
 * Upload a file from an indexed input (e.g., v_image1[]), returning '' if none.
 */
function uploadIndexedFile($field, $idx, $upload_dir = "uploads/") {
    if (!isset($_FILES[$field])) return ''; // <-- fixed: removed stray 0
    // Expect arrays: name[idx], tmp_name[idx]
    if (!isset($_FILES[$field]['name'][$idx]) || $_FILES[$field]['name'][$idx] === '') return '';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $safe = preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES[$field]['name'][$idx]));
    $filename = time() . '_' . mt_rand(1000,9999) . '_' . $safe;
    $target = rtrim($upload_dir, '/') . '/' . $filename;
    if (move_uploaded_file($_FILES[$field]['tmp_name'][$idx], $target)) return $target;
    return '';
}

$alerts = [];

// ------------------ LOAD MASTER + VARIANTS ------------------
// master
$masterStmt = mysqli_prepare($conn, "SELECT product_name, variation_id, variation_name, mrp_price, actual_price, primary_image1, primary_image2, primary_image3, primary_image4 FROM accessories_products_master WHERE product_code = ?");
if (!$masterStmt) die("Prepare failed (master select): " . mysqli_error($conn));
mysqli_stmt_bind_param($masterStmt, 's', $product_code);
mysqli_stmt_execute($masterStmt);
mysqli_stmt_bind_result($masterStmt, $m_product_name, $m_variation_id, $m_variation_name, $m_mrp, $m_act, $m_i1, $m_i2, $m_i3, $m_i4);
$hasMaster = mysqli_stmt_fetch($masterStmt);
mysqli_stmt_close($masterStmt);

// variants
$vstmt = mysqli_prepare($conn, "SELECT id, variation_code, attribute, inventory_count, image1, image2, image3, image4, product_name, variation_name, mrp_price, actual_price FROM accessories_products WHERE product_code = ? ORDER BY id");
if (!$vstmt) die("Prepare failed (variants select): " . mysqli_error($conn));
mysqli_stmt_bind_param($vstmt, 's', $product_code);
mysqli_stmt_execute($vstmt);
mysqli_stmt_bind_result($vstmt, $vid, $vcode, $vattr, $vinv, $vi1, $vi2, $vi3, $vi4, $v_product_name, $v_variation_name, $v_mrp, $v_act);

$variants = [];
while (mysqli_stmt_fetch($vstmt)) {
    $variants[] = [
        'id' => $vid,
        'variation_code' => $vcode,
        'attribute' => $vattr,
        'inventory_count' => $vinv,
        'images' => [$vi1,$vi2,$vi3,$vi4], // secondary images
        'product_name' => $v_product_name,
        'variation_name' => $v_variation_name,
        'mrp_price' => $v_mrp,
        'actual_price' => $v_act
    ];
}
mysqli_stmt_close($vstmt);

// prepare product block
$product = [
    'product_name'   => $hasMaster ? $m_product_name : ($variants[0]['product_name'] ?? ''),
    'variation_id'   => $hasMaster ? $m_variation_id : 0,
    'mrp_price'      => $hasMaster ? $m_mrp : ($variants[0]['mrp_price'] ?? 0.0),
    'actual_price'   => $hasMaster ? $m_act : ($variants[0]['actual_price'] ?? 0.0),
    'primary_images' => [
        $m_i1 ?? ($variants[0]['images'][0] ?? ''),
        $m_i2 ?? ($variants[0]['images'][1] ?? ''),
        $m_i3 ?? ($variants[0]['images'][2] ?? ''),
        $m_i4 ?? ($variants[0]['images'][3] ?? ''),
    ],
    'variations'     => $variants
];

// categories
$cats = mysqli_query($conn, "SELECT id, name FROM accessories ORDER BY name");
if (!$cats) $alerts[] = ['type'=>'danger','text'=>'Failed to load categories: ' . mysqli_error($conn)];

// ------------------ HANDLE POST (UPDATE) ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted_variation_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($posted_variation_id <= 0) $alerts[] = ['type'=>'danger','text'=>'Please select a valid category.'];

    $posted_product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $posted_mrp  = isset($_POST['mrp_price']) ? (float)$_POST['mrp_price'] : 0.0;
    $posted_act  = isset($_POST['actual_price']) ? (float)$_POST['actual_price'] : 0.0;

    if ($posted_product_name === '') $alerts[] = ['type'=>'danger','text'=>'Product name is required.'];
    if ($posted_mrp <= 0) $alerts[] = ['type'=>'danger','text'=>'MRP must be > 0.'];
    if ($posted_act < 0) $alerts[] = ['type'=>'danger','text'=>'Actual price must be >= 0.'];
    if ($posted_act > $posted_mrp) $alerts[] = ['type'=>'danger','text'=>'Actual price cannot be greater than MRP.'];

    // master images (primary)
    $primary_images = $product['primary_images'];
    for ($i = 1; $i <= 4; $i++) {
        $field = "image{$i}";
        $uploaded = uploadImageFile($field);
        if ($uploaded !== '') $primary_images[$i-1] = $uploaded;
    }

    if (empty($alerts)) {
        mysqli_begin_transaction($conn);
        try {
            // variation name lookup
            $variation_name_lookup = '';
            $tv = mysqli_prepare($conn, "SELECT name FROM accessories WHERE id = ?");
            if ($tv) {
                mysqli_stmt_bind_param($tv, 'i', $posted_variation_id);
                mysqli_stmt_execute($tv);
                mysqli_stmt_bind_result($tv, $variation_name_lookup);
                mysqli_stmt_fetch($tv);
                mysqli_stmt_close($tv);
            }

            // upsert master
            $upsertSql = "INSERT INTO accessories_products_master
                (product_code, product_name, variation_id, variation_name, mrp_price, actual_price,
                 primary_image1, primary_image2, primary_image3, primary_image4, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE
                  product_name=VALUES(product_name),
                  variation_id=VALUES(variation_id),
                  variation_name=VALUES(variation_name),
                  mrp_price=VALUES(mrp_price),
                  actual_price=VALUES(actual_price),
                  primary_image1=VALUES(primary_image1),
                  primary_image2=VALUES(primary_image2),
                  primary_image3=VALUES(primary_image3),
                  primary_image4=VALUES(primary_image4)";
            $up = mysqli_prepare($conn, $upsertSql);
            if (!$up) throw new Exception("Prepare failed (master upsert): " . mysqli_error($conn));
            mysqli_stmt_bind_param($up, 'ssisddssss',
                $product_code,
                $posted_product_name,
                $posted_variation_id,
                $variation_name_lookup,
                $posted_mrp,
                $posted_act,
                $primary_images[0],
                $primary_images[1],
                $primary_images[2],
                $primary_images[3]
            );
            if (!mysqli_stmt_execute($up)) throw new Exception("Master upsert failed: " . mysqli_stmt_error($up));
            mysqli_stmt_close($up);

            // wipe variants
            $del = mysqli_prepare($conn, "DELETE FROM accessories_products WHERE product_code = ?");
            if (!$del) throw new Exception("Prepare failed (delete variants): " . mysqli_error($conn));
            mysqli_stmt_bind_param($del, 's', $product_code);
            if (!mysqli_stmt_execute($del)) throw new Exception("Delete variants failed: " . mysqli_stmt_error($del));
            mysqli_stmt_close($del);

            // insert variants (with secondary images)
            if (isset($_POST['variation_code']) && is_array($_POST['variation_code'])) {
                $ins = mysqli_prepare($conn, "INSERT INTO accessories_products
                    (variation_id, variation_name, product_code, variation_code, product_name,
                     image1, image2, image3, image4, attribute, inventory_count, mrp_price, actual_price)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                if (!$ins) throw new Exception("Prepare failed (insert variant): " . mysqli_error($conn));

                foreach ($_POST['variation_code'] as $idx => $vcodeRaw) {
                    $vcode = trim($vcodeRaw);
                    if ($vcode === '') continue;

                    $attr = isset($_POST['attribute'][$idx]) ? trim($_POST['attribute'][$idx]) : '';
                    $inv  = isset($_POST['inventory_count'][$idx]) ? (int)$_POST['inventory_count'][$idx] : 0;

                    // existing image paths from hidden inputs (if present)
                    $cur1 = $_POST['current_v_image1'][$idx] ?? '';
                    $cur2 = $_POST['current_v_image2'][$idx] ?? '';
                    $cur3 = $_POST['current_v_image3'][$idx] ?? '';
                    $cur4 = $_POST['current_v_image4'][$idx] ?? '';

                    // new uploads (override if provided)
                    $v_image1 = uploadIndexedFile('v_image1', $idx) ?: $cur1;
                    $v_image2 = uploadIndexedFile('v_image2', $idx) ?: $cur2;
                    $v_image3 = uploadIndexedFile('v_image3', $idx) ?: $cur3;
                    $v_image4 = uploadIndexedFile('v_image4', $idx) ?: $cur4;

                    mysqli_stmt_bind_param($ins, 'isssssssssidd',
                        $posted_variation_id,
                        $variation_name_lookup,
                        $product_code,
                        $vcode,
                        $posted_product_name,
                        $v_image1,
                        $v_image2,
                        $v_image3,
                        $v_image4,
                        $attr,
                        $inv,
                        $posted_mrp,
                        $posted_act
                    );

                    if (!mysqli_stmt_execute($ins)) {
                        $err = mysqli_stmt_error($ins);
                        mysqli_stmt_close($ins);
                        throw new Exception("Insert variant failed for {$vcode}: " . $err);
                    }
                }
                mysqli_stmt_close($ins);
            }

            mysqli_commit($conn);
            header("Location: view_accessories_products.php?updated=1");
            exit;

        } catch (Exception $ex) {
            mysqli_rollback($conn);
            $alerts[] = ['type'=>'danger','text'=>'Update failed: '.$ex->getMessage()];
        }
    }
}

include("header.php");
?>

<div class="main-content app-content" style="background-color:rgb(42, 42, 44); min-height:100vh; color:#fff;">
  <div class="container-fluid py-4">
    <h4 class="mb-3"><b>Update Accessories</b></h4>

    <?php foreach ($alerts as $a): ?>
      <div class="alert alert-<?= htmlspecialchars($a['type']); ?>"><?= htmlspecialchars($a['text']); ?></div>
    <?php endforeach; ?>

    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="id" class="form-select" required>
            <?php
            if ($cats) {
              mysqli_data_seek($cats, 0);
              while ($c = mysqli_fetch_assoc($cats)): ?>
                <option value="<?= (int)$c['id']; ?>" <?= ((int)$c['id'] === (int)$product['variation_id']) ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($c['name']); ?>
                </option>
              <?php endwhile;
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']); ?>" required>
        </div>

        <!-- PRIMARY (MASTER) IMAGES -->
        <div class="row">
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="col-md-3 mb-3">
              <label class="form-label">Primary Image <?= $i; ?></label><br>
              <?php if (!empty($product['primary_images'][$i-1])): ?>
                <img src="<?= htmlspecialchars($product['primary_images'][$i-1]); ?>" style="width:100px;height:100px;object-fit:cover;" class="mb-2"><br>
              <?php endif; ?>
              <input type="file" name="image<?= $i; ?>" class="form-control">
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
        <h5>Variations (with Secondary Images)</h5>
        <div class="table-responsive mb-3">
          <table class="table table-bordered align-middle" id="variationsTable">
            <thead class="table-light">
              <tr>
                <th>Variation Code</th>
                <th>Attribute</th>
                <th>Inventory</th>
                <th>Secondary Image 1</th>
                <th>Secondary Image 2</th>
                <th>Secondary Image 3</th>
                <th>Secondary Image 4</th>
                <th style="width:50px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($product['variations'])): ?>
                <?php foreach ($product['variations'] as $idx => $v): ?>
                  <tr>
                    <td><input type="text" name="variation_code[]" value="<?= htmlspecialchars($v['variation_code']); ?>" class="form-control" required></td>
                    <td><input type="text" name="attribute[]" value="<?= htmlspecialchars($v['attribute']); ?>" class="form-control"></td>
                    <td style="max-width:110px;"><input type="number" name="inventory_count[]" value="<?= htmlspecialchars((string)$v['inventory_count']); ?>" class="form-control"></td>

                    <?php for ($i=0;$i<4;$i++): ?>
                      <td>
                        <?php $img = $v['images'][$i] ?? ''; ?>
                        <?php if (!empty($img)): ?>
                          <img src="<?= htmlspecialchars($img); ?>" style="width:60px;height:60px;object-fit:cover;display:block;margin-bottom:6px;">
                        <?php endif; ?>
                        <input type="file" name="v_image<?= $i+1; ?>[]" class="form-control form-control-sm">
                        <input type="hidden" name="current_v_image<?= $i+1; ?>[]" value="<?= htmlspecialchars($img); ?>">
                      </td>
                    <?php endfor; ?>

                    <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td><input type="text" name="variation_code[]" class="form-control" required></td>
                  <td><input type="text" name="attribute[]" class="form-control"></td>
                  <td><input type="number" name="inventory_count[]" class="form-control"></td>

                  <?php for ($i=1;$i<=4;$i++): ?>
                    <td>
                      <input type="file" name="v_image<?= $i; ?>[]" class="form-control form-control-sm">
                      <input type="hidden" name="current_v_image<?= $i; ?>[]" value="">
                    </td>
                  <?php endfor; ?>

                  <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <button type="button" class="btn btn-info mb-3" id="addVariation">Add More Variation</button>
        <br>
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="view_accessories_products.php" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const addBtn = document.getElementById('addVariation');
  const tbody = document.getElementById('variationsTable').querySelector('tbody');

  addBtn.addEventListener('click', function() {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" name="variation_code[]" class="form-control" required></td>
      <td><input type="text" name="attribute[]" class="form-control"></td>
      <td><input type="number" name="inventory_count[]" class="form-control"></td>

      <td>
        <input type="file" name="v_image1[]" class="form-control form-control-sm">
        <input type="hidden" name="current_v_image1[]" value="">
      </td>
      <td>
        <input type="file" name="v_image2[]" class="form-control form-control-sm">
        <input type="hidden" name="current_v_image2[]" value="">
      </td>
      <td>
        <input type="file" name="v_image3[]" class="form-control form-control-sm">
        <input type="hidden" name="current_v_image3[]" value="">
      </td>
      <td>
        <input type="file" name="v_image4[]" class="form-control form-control-sm">
        <input type="hidden" name="current_v_image4[]" value="">
      </td>

      <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
    `;
    tbody.appendChild(newRow);
  });

  tbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
      e.target.closest('tr').remove();
    }
  });
});
</script>

<?php include("footer.php"); ?>
