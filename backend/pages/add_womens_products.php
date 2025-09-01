<?php 
include("header.php");
include 'db.php';

// Show mysqli exceptions during development (remove in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// --- Default product_code so it's always defined (fixes the undefined variable notice) ---
$product_code = 'WOMEN' . time();

// Helper: upload image, returns stored path or empty string
function uploadImage($inputName) {
    if (!empty($_FILES[$inputName]['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir,0777,true);
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES[$inputName]['name']));
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) return $targetFile;
    }
    return '';
}

// Message container
$alerts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use existing $product_code as default if no product_code in POST
    $product_code = trim($_POST['product_code'] ?? $product_code);
    $variation_id = (int)($_POST['variation_id'] ?? 0); // category id selected from women.id
    $product_name = trim($_POST['product_name'] ?? '');
    $mrp_price    = isset($_POST['mrp_price']) ? (float)$_POST['mrp_price'] : 0.0;
    $actual_price = isset($_POST['actual_price']) ? (float)$_POST['actual_price'] : 0.0;

    // Upload images (only if provided)
    $image1 = uploadImage('image1');
    $image2 = uploadImage('image2');
    $image3 = uploadImage('image3');
    $image4 = uploadImage('image4');

    // Basic validation
    if ($variation_id <= 0) {
        $alerts[] = ['type'=>'danger','text'=>'Please select a category.'];
    }
    if ($product_code === '' || $product_name === '') {
        $alerts[] = ['type'=>'danger','text'=>'Product code and name are required.'];
    }
    if ($mrp_price <= 0) {
        $alerts[] = ['type'=>'danger','text'=>'Please enter a valid MRP.'];
    }
    if ($actual_price < 0) {
        $alerts[] = ['type'=>'danger','text'=>'Please enter a valid Actual price.'];
    }
    if ($actual_price > $mrp_price) {
        $alerts[] = ['type'=>'danger','text'=>'Actual price cannot be greater than MRP.'];
    }

    // Validate category exists and get its name
    if (empty($alerts)) {
        $catStmt = mysqli_prepare($conn, "SELECT name FROM women WHERE id = ?");
        mysqli_stmt_bind_param($catStmt, "i", $variation_id);
        mysqli_stmt_execute($catStmt);
        mysqli_stmt_bind_result($catStmt, $variation_name);
        if (!mysqli_stmt_fetch($catStmt)) {
            $alerts[] = ['type'=>'danger','text'=>'Selected category not found in database.'];
        }
        mysqli_stmt_close($catStmt);
    }

    // Process variations
    if (empty($alerts)) {
        if (!empty($_POST['attribute']) && is_array($_POST['attribute'])) {
            // NOTE: added `weight` column in the INSERT list and values -> added one more placeholder
            $sql = "INSERT INTO womens_products 
                (variation_id, variation_name, product_code, variation_code, product_name, image1, image2, image3, image4, attribute, inventory_count, weight, mrp_price, actual_price) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) {
                $alerts[] = ['type'=>'danger','text'=>'Prepare failed: '.mysqli_error($conn)];
            } else {
                $inserted = 0;
                foreach ($_POST['attribute'] as $i => $attrRaw) {
                    $attr = trim($attrRaw);
                    if ($attr === '') continue;

                    $inv = isset($_POST['inventory_count'][$i]) ? (int)$_POST['inventory_count'][$i] : 0;
                    // Read weight (allow decimals)
                    $weight = isset($_POST['weight'][$i]) ? (float)$_POST['weight'][$i] : 0.0;

                    // generate variation_code unique per variation
                    $variation_code = $product_code . '-' . ($i + 1);

                    // bind params: i (variation_id), s (variation_name), s (product_code), s (variation_code),
                    // s (product_name), s(image1), s(image2), s(image3), s(image4), s(attribute),
                    // i(inventory_count), d(weight), d(mrp_price), d(actual_price)
                    // types string: i + 9*s + i + d + d + d => "isssssssssiddd"
                    mysqli_stmt_bind_param(
                        $stmt,
                        "isssssssssiddd",
                        $variation_id,
                        $variation_name,
                        $product_code,
                        $variation_code,
                        $product_name,
                        $image1,
                        $image2,
                        $image3,
                        $image4,
                        $attr,
                        $inv,
                        $weight,
                        $mrp_price,
                        $actual_price
                    );

                    // execute
                    try {
                        mysqli_stmt_execute($stmt);
                        $inserted++;
                    } catch (mysqli_sql_exception $e) {
                        // If variation_code unique constraint fails, you can skip or handle
                        $alerts[] = ['type'=>'danger','text'=>"Failed inserting variation '{$attr}': ".$e->getMessage()];
                    }
                } // foreach

                mysqli_stmt_close($stmt);

                if ($inserted > 0) {
                    $alerts[] = ['type'=>'success','text'=>"Product and {$inserted} variation(s) saved successfully."];
                    // regenerate product_code for next new entry so user doesn't reuse old code accidentally
                    $product_code = 'WOMEN' . time();
                } else {
                    $alerts[] = ['type'=>'warning','text'=>'No valid variations were provided.'];
                }
            }
        } else {
            $alerts[] = ['type'=>'warning','text'=>'Please add at least one variation.'];
        }
    }
} // end POST

// fetch categories for dropdown
$cats = mysqli_query($conn,"SELECT id,name FROM women ORDER BY name");
?>

<div class="main-content app-content" style="background-color:rgb(42, 42, 44); min-height:100vh; color:#fff;">
  <div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold">Add Women's Product</h2>

    <!-- Alerts -->
    <?php foreach($alerts as $a): ?>
      <div class="alert alert-<?= htmlspecialchars($a['type']); ?>">
        <?= htmlspecialchars($a['text']); ?>
      </div>
    <?php endforeach; ?>

    <form id="product-form" action="" method="POST" enctype="multipart/form-data" onsubmit="return validatePrices();">
      <input type="hidden" name="product_code" value="<?= htmlspecialchars($product_code); ?>">

      <div class="mb-4">
        <label class="mb-2">Select Category</label>
        <select name="variation_id" class="form-select" required>
          <option value="">--Choose--</option>
          <?php while($row=mysqli_fetch_assoc($cats)): ?>
          <option value="<?= (int)$row['id'];?>"><?= htmlspecialchars($row['name']);?></option>
          <?php endwhile;?>
        </select>
      </div>

      <div class="mb-4">
        <label class="mb-2">Product Name</label>
        <input type="text" name="product_name" class="form-control" required>
      </div>

      <div class="row mb-4">
        <div class="col-md-3"><label class="mb-2">Image1</label><input type="file" name="image1" class="form-control"></div>
        <div class="col-md-3"><label class="mb-2">Image2</label><input type="file" name="image2" class="form-control"></div>
        <div class="col-md-3"><label class="mb-2">Image3</label><input type="file" name="image3" class="form-control"></div>
        <div class="col-md-3"><label class="mb-2">Image4</label><input type="file" name="image4" class="form-control"></div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <label class="mb-2">MRP</label>
          <input id="mrp_price" type="number" step="0.01" name="mrp_price" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="mb-2">Actual Price</label>
          <input id="actual_price" type="number" step="0.01" name="actual_price" class="form-control" required>
        </div>
      </div>

      <div id="variation-container">
        <label class="mb-2">Variations</label>
        <div class="row g-2 mb-4 variation-row">
          <div class="col-md-4">
            <input type="text" name="attribute[]" class="form-control" placeholder="Variation (e.g., S, M, L)" required>
          </div>
          <div class="col-md-2">
            <input type="number" name="inventory_count[]" class="form-control" placeholder="Inventory" required>
          </div>
          <div class="col-md-3">
            <input type="number" step="0.01" name="weight[]" class="form-control" placeholder="Weight (kg)" required>
          </div>
          <div class="col-md-3 d-grid">
            <button type="button" class="btn btn-danger remove-row">&times;</button>
          </div>
        </div>
      </div>

      <button type="button" id="add-variation" class="btn btn-secondary mt-2 mb-3">+ Add Variation</button>
      <button type="submit" class="btn btn-success w-10 mt-3 mb-4">Save Product</button>
    </form>
  </div>
</div>

<script>
  // Add / remove variation rows
  document.getElementById('add-variation').addEventListener('click',()=>{
    const row=document.createElement('div');
    row.classList.add('row','g-2','mb-2','variation-row');
    row.innerHTML=`<div class="col-md-4"><input type="text" name="attribute[]" class="form-control" placeholder="Variation (e.g., S, M, L / Red, Blue)" required></div>
    <div class="col-md-2"><input type="number" name="inventory_count[]" class="form-control" placeholder="Inventory" required></div>
    <div class="col-md-3"><input type="number" step="0.01" name="weight[]" class="form-control" placeholder="Weight (kg)" required></div>
    <div class="col-md-3 d-grid"><button type="button" class="btn btn-danger remove-row">&times;</button></div>`;
    document.getElementById('variation-container').appendChild(row);
  });
  document.getElementById('variation-container').addEventListener('click',e=>{
    if(e.target.classList.contains('remove-row')){
      // ensure at least one remains
      const rows = document.querySelectorAll('.variation-row');
      if (rows.length <= 1) {
        alert('At least one variation is required.');
        return;
      }
      e.target.closest('.variation-row').remove();
    }
  });

  // Client-side price validation before submit
  function validatePrices() {
    const mrp = parseFloat(document.getElementById('mrp_price').value || 0);
    const act = parseFloat(document.getElementById('actual_price').value || 0);
    if (act > mrp) {
      alert('Actual price cannot be greater than MRP.');
      return false;
    }
    return true;
  }
</script>

<?php include("footer.php"); ?>
