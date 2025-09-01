<?php
// add_toys_product.php
include("header.php");
include 'db.php';

// show mysqli exceptions for dev
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$product_code = 'TOYS' . time();

function uploadImageField($fieldName) {
    if (!empty($_FILES[$fieldName]['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir,0777,true);
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES[$fieldName]['name']));
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetFile)) return $targetFile;
    }
    return '';
}

// upload per-variation image when input names are arrays: image1[$idx], image2[$idx], ...
function uploadVariationImage($fieldName, $index) {
    if (!isset($_FILES[$fieldName])) return '';
    if (!isset($_FILES[$fieldName]['name'][$index]) || $_FILES[$fieldName]['name'][$index] === '') return '';
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir,0777,true);
    $orig = $_FILES[$fieldName]['name'][$index];
    $tmp  = $_FILES[$fieldName]['tmp_name'][$index];
    $filename = time() . '_' . $index . '_' . preg_replace('/[^A-Za-z0-9._-]/','_', basename($orig));
    $targetFile = $targetDir . $filename;
    if (move_uploaded_file($tmp, $targetFile)) return $targetFile;
    return '';
}

$alerts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_code = trim($_POST['product_code'] ?? $product_code);
    $variation_id = (int)($_POST['variation_id'] ?? 0); // category id from toy table
    $product_name = trim($_POST['product_name'] ?? '');
    $mrp_price    = isset($_POST['mrp_price']) ? (float)$_POST['mrp_price'] : 0.0;
    $actual_price = isset($_POST['actual_price']) ? (float)$_POST['actual_price'] : 0.0;

    // Basic validation
    if ($variation_id <= 0) $alerts[] = ['type'=>'danger','text'=>'Please select a category.'];
    if ($product_code === '' || $product_name === '') $alerts[] = ['type'=>'danger','text'=>'Product code and name are required.'];
    if ($mrp_price <= 0) $alerts[] = ['type'=>'danger','text'=>'Please enter a valid MRP.'];
    if ($actual_price < 0) $alerts[] = ['type'=>'danger','text'=>'Please enter a valid Actual price.'];
    if ($actual_price > $mrp_price) $alerts[] = ['type'=>'danger','text'=>'Actual price cannot be greater than MRP.'];

    // fetch category name
    if (empty($alerts)) {
        $catStmt = mysqli_prepare($conn, "SELECT name FROM toy WHERE id = ?");
        mysqli_stmt_bind_param($catStmt, "i", $variation_id);
        mysqli_stmt_execute($catStmt);
        mysqli_stmt_bind_result($catStmt, $variation_name);
        if (!mysqli_stmt_fetch($catStmt)) {
            $alerts[] = ['type'=>'danger','text'=>'Selected category not found in database.'];
        }
        mysqli_stmt_close($catStmt);
    }

    // Save primary images (product-level) — up to 4
    if (empty($alerts)) {
        $primary1 = uploadImageField('primary_image1');
        $primary2 = uploadImageField('primary_image2');
        $primary3 = uploadImageField('primary_image3');
        $primary4 = uploadImageField('primary_image4');

        // quick debug (remove in production)
        if ($primary1 === '') {
            error_log("DEBUG: primary_image1 empty after upload. \$_FILES keys: " . json_encode(array_keys($_FILES)));
        }

        // create master table if missing (best-effort)
        $createMaster = "CREATE TABLE IF NOT EXISTS toys_products_master (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_code VARCHAR(100) UNIQUE,
            product_name VARCHAR(255),
            variation_id INT,
            variation_name VARCHAR(255),
            mrp_price DECIMAL(10,2),
            actual_price DECIMAL(10,2),
            primary_image1 VARCHAR(255),
            primary_image2 VARCHAR(255),
            primary_image3 VARCHAR(255),
            primary_image4 VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        mysqli_query($conn, $createMaster);

        // insert into master table
        $mSql = "INSERT INTO toys_products_master (product_code, product_name, variation_id, variation_name, mrp_price, actual_price, primary_image1, primary_image2, primary_image3, primary_image4) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $mStmt = mysqli_prepare($conn, $mSql);
        if (!$mStmt) {
            $alerts[] = ['type'=>'danger','text'=>'Master insert prepare failed: '.mysqli_error($conn)];
        } else {
            // types: s(product_code), s(product_name), i(variation_id), s(variation_name),
            // d(mrp_price), d(actual_price), s(primary1), s(primary2), s(primary3), s(primary4)
            mysqli_stmt_bind_param($mStmt, 'ssisddssss', $product_code, $product_name, $variation_id, $variation_name, $mrp_price, $actual_price, $primary1, $primary2, $primary3, $primary4);

            if (!mysqli_stmt_execute($mStmt)) {
                $err = mysqli_stmt_error($mStmt);
                // If duplicate product_code, attempt an update instead
                if (stripos($err, 'Duplicate') !== false || stripos($err, 'duplicate') !== false) {
                    mysqli_stmt_close($mStmt);
                    $uSql = "UPDATE toys_products_master SET product_name=?, variation_id=?, variation_name=?, mrp_price=?, actual_price=?, primary_image1=?, primary_image2=?, primary_image3=?, primary_image4=? WHERE product_code=?";
                    $uStmt = mysqli_prepare($conn, $uSql);
                    if (!$uStmt) {
                        $alerts[] = ['type'=>'danger','text'=>'Master update prepare failed: '.mysqli_error($conn)];
                    } else {
                        // types: s(product_name), i(variation_id), s(variation_name), d(mrp_price), d(actual_price),
                        // s(primary1), s(primary2), s(primary3), s(primary4), s(product_code)
                        mysqli_stmt_bind_param($uStmt, 'sisddsssss', $product_name, $variation_id, $variation_name, $mrp_price, $actual_price, $primary1, $primary2, $primary3, $primary4, $product_code);
                        if (!mysqli_stmt_execute($uStmt)) {
                            $alerts[] = ['type'=>'danger','text'=>'Master update failed: '.mysqli_stmt_error($uStmt)];
                        } else {
                            $alerts[] = ['type'=>'success','text'=>'Master updated (duplicate handled).'];
                        }
                        mysqli_stmt_close($uStmt);
                    }
                } else {
                    $alerts[] = ['type'=>'danger','text'=>'Master insert failed: '.$err];
                }
            } else {
                $alerts[] = ['type'=>'success','text'=>'Master product inserted.'];
            }
            mysqli_stmt_close($mStmt);
        }
    }

    // process variants (multiple rows) — secondary images per color stored in toys_products.image1..image4
    if (empty($alerts) || true) {
        // note: even if master had warnings, we still allow adding variants — adjust as desired
        if (!empty($_POST['variant_type']) && is_array($_POST['variant_type'])) {
            $sql = "INSERT INTO toys_products 
                (variation_id, variation_name, product_code, variation_code, product_name, image1, image2, image3, image4, attribute, inventory_count, weight, mrp_price, actual_price)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) {
                $alerts[] = ['type'=>'danger','text'=>'Prepare failed: '.mysqli_error($conn)];
            } else {
                $inserted = 0;
                foreach ($_POST['variant_type'] as $i => $vtypeRaw) {
                    $vtype = trim($vtypeRaw);
                    if ($vtype !== 'color' && $vtype !== 'size') continue;

                    $attr = trim($_POST['attribute'][$i] ?? '');
                    if ($attr === '') {
                        $alerts[] = ['type'=>'warning','text'=>"Variant #".($i+1)." missing attribute (size or color). Skipped."];
                        continue;
                    }

                    $inv = isset($_POST['inventory_count'][$i]) ? (int)$_POST['inventory_count'][$i] : 0;
                    $wt  = isset($_POST['weight'][$i]) ? (float)$_POST['weight'][$i] : 0.0;

                    if ($vtype === 'color') {
                        $v_image1 = uploadVariationImage('image1', $i);
                        $v_image2 = uploadVariationImage('image2', $i);
                        $v_image3 = uploadVariationImage('image3', $i);
                        $v_image4 = uploadVariationImage('image4', $i);
                    } else {
                        $v_image1 = '';
                        $v_image2 = '';
                        $v_image3 = '';
                        $v_image4 = '';
                    }

                    $variation_code = $product_code . '-' . ($i + 1);

                    // bind params types: i(variation_id), s(variation_name), s(product_code), s(variation_code), s(product_name),
                    // s(image1), s(image2), s(image3), s(image4), s(attribute), i(inventory_count), d(weight), d(mrp_price), d(actual_price)
                    // types string: i + 9s + i + d + d + d  => 'isssssssssiddd'
                    mysqli_stmt_bind_param(
                        $stmt,
                        "isssssssssiddd",
                        $variation_id,
                        $variation_name,
                        $product_code,
                        $variation_code,
                        $product_name,
                        $v_image1,
                        $v_image2,
                        $v_image3,
                        $v_image4,
                        $attr,
                        $inv,
                        $wt,
                        $mrp_price,
                        $actual_price
                    );

                    try {
                        mysqli_stmt_execute($stmt);
                        $inserted++;
                    } catch (mysqli_sql_exception $e) {
                        $alerts[] = ['type'=>'danger','text'=>"Failed inserting variant #".($i+1)." ({$attr}): ".$e->getMessage()];
                    }
                }

                mysqli_stmt_close($stmt);

                if ($inserted > 0) {
                    $alerts[] = ['type'=>'success','text'=>"Product master saved and {$inserted} variant(s) inserted successfully."];
                    $product_code = 'TOYS' . time();
                } else {
                    // if no variants were inserted but master succeeded, keep master message separate
                    $alerts[] = ['type'=>'warning','text'=>'No valid variants were inserted.'];
                }
            }
        } else {
            // If you require at least one variant, you can make this an error instead
            $alerts[] = ['type'=>'warning','text'=>'Please add at least one variant.'];
        }
    }
}

// fetch categories for dropdown
$cats = mysqli_query($conn,"SELECT id,name FROM toy ORDER BY name");
?>

<div class="main-content app-content" style="background-color:rgb(42, 42, 44); min-height:100vh; color:#fff;">
  <div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold">Add Toy's Product</h2>

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

      <label class="mb-2">Primary Images</label>
      <div class="row g-2 mb-3">
        <div class="col-md-3"><input type="file" name="primary_image1" class="form-control" accept="image/*"></div>
        <div class="col-md-3"><input type="file" name="primary_image2" class="form-control" accept="image/*"></div>
        <div class="col-md-3"><input type="file" name="primary_image3" class="form-control" accept="image/*"></div>
        <div class="col-md-3"><input type="file" name="primary_image4" class="form-control" accept="image/*"></div>
      </div>
      <br>
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

      <label class="mb-2">Variants (add color or size) — color variants accept secondary images</label>
      <div id="variant-container">
        <!-- initial variant row -->
        <div class="row g-2 mb-3 variant-row">
          <div class="col-md-2">
            <select name="variant_type[]" class="form-select variant-type">
              <option value="color">Color</option>
              <option value="size">Size</option>
            </select>
          </div>

          <!-- attribute: color name or size -->
          <div class="col-md-3">
            <input type="text" name="attribute[]" class="form-control" placeholder="Color name or Size (e.g., Red / M)" required>
          </div>

          <div class="col-md-2">
            <input type="number" name="inventory_count[]" class="form-control" placeholder="Inventory" required>
          </div>

          <div class="col-md-2">
            <input type="number" step="0.01" name="weight[]" class="form-control" placeholder="Weight (kg)" required>
          </div>

          <!-- per-variant images container (shown for color) -->
          <div class="col-md-3 variant-images">
            <div class="row g-1">
              <div class="col-6"><input type="file" name="image1[]" class="form-control" accept="image/*"></div>
              <div class="col-6"><input type="file" name="image2[]" class="form-control" accept="image/*"></div>
              <div class="col-6 mt-1"><input type="file" name="image3[]" class="form-control" accept="image/*"></div>
              <div class="col-6 mt-1"><input type="file" name="image4[]" class="form-control" accept="image/*"></div>
            </div>
          </div>

          <div class="col-md-12 mt-2">
            <button type="button" class="btn btn-danger remove-variant">Remove Variant</button>
          </div>
        </div>
      </div>

      <button type="button" id="add-variant" class="btn btn-secondary mt-2 mb-3">+ Add Variant</button>
      <button type="submit" class="btn btn-success w-10 mt-3 mb-4">Save Product</button>
    </form>
  </div>
</div>

<script>
  // add variant row template
  function makeVariantRow() {
    const row = document.createElement('div');
    row.classList.add('row','g-2','mb-3','variant-row');
    row.innerHTML = `
      <div class="col-md-2">
        <select name="variant_type[]" class="form-select variant-type">
          <option value="color">Color</option>
          <option value="size">Size</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="text" name="attribute[]" class="form-control" placeholder="Color name or Size (e.g., Red / M)" required>
      </div>
      <div class="col-md-2">
        <input type="number" name="inventory_count[]" class="form-control" placeholder="Inventory" required>
      </div>
      <div class="col-md-2">
        <input type="number" step="0.01" name="weight[]" class="form-control" placeholder="Weight (kg)" required>
      </div>
      <div class="col-md-3 variant-images">
        <div class="row g-1">
          <div class="col-6"><input type="file" name="image1[]" class="form-control" accept="image/*"></div>
          <div class="col-6"><input type="file" name="image2[]" class="form-control" accept="image/*"></div>
          <div class="col-6 mt-1"><input type="file" name="image3[]" class="form-control" accept="image/*"></div>
          <div class="col-6 mt-1"><input type="file" name="image4[]" class="form-control" accept="image/*"></div>
        </div>
      </div>
      <div class="col-md-12 mt-2">
        <button type="button" class="btn btn-danger remove-variant">Remove Variant</button>
      </div>
    `;
    return row;
  }

  document.getElementById('add-variant').addEventListener('click', ()=>{
    document.getElementById('variant-container').appendChild(makeVariantRow());
  });

  // delegate remove and variant-type toggle
  document.getElementById('variant-container').addEventListener('click', (e)=>{
    if (e.target.classList.contains('remove-variant')) {
      const rows = document.querySelectorAll('.variant-row');
      if (rows.length <= 1) {
        alert('At least one variant required.');
        return;
      }
      e.target.closest('.variant-row').remove();
    }
  });

  // toggle images visibility depending on variant_type
  document.getElementById('variant-container').addEventListener('change', (e)=>{
    if (e.target.classList.contains('variant-type')) {
      const row = e.target.closest('.variant-row');
      const vImages = row.querySelector('.variant-images');
      if (e.target.value === 'color') {
        vImages.style.display = ''; // show
      } else {
        // clear file inputs and hide for size variants
        const files = vImages.querySelectorAll('input[type="file"]');
        files.forEach(f => { f.value = ''; });
        vImages.style.display = 'none';
      }
    }
  });

  // initialize: hide images if variant_type= size (first row default is color so visible)
  document.querySelectorAll('.variant-row').forEach(row=>{
    const sel = row.querySelector('.variant-type');
    const vImages = row.querySelector('.variant-images');
    if (sel && sel.value === 'size') vImages.style.display = 'none';
  });

  // price validation
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
