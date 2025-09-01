<?php
// view_toys_products.php (primary + secondary images)
include 'db.php';

// determine total distinct product_code from master if exists, otherwise from toys_products
$totalProducts = 0;
$checkMaster = mysqli_query($conn, "SHOW TABLES LIKE 'toys_products_master'");
if (mysqli_num_rows($checkMaster) > 0) {
    $res = mysqli_query($conn, "SELECT COUNT(DISTINCT product_code) AS total FROM toys_products_master");
} else {
    $res = mysqli_query($conn, "SELECT COUNT(DISTINCT product_code) AS total FROM toys_products");
}
if ($res) {
    $rowCount = mysqli_fetch_assoc($res);
    $totalProducts = (int)$rowCount['total'];
}

// pagination config
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$totalPages = ($totalProducts > 0) ? (int)ceil($totalProducts / $limit) : 1;

// handle deletion (via GET param ?code=...), use prepared stmt for safety
if (isset($_GET['code']) && $_GET['code'] !== '') {
    $code = $_GET['code'];

    // Delete variants
    $delVariants = mysqli_prepare($conn, "DELETE FROM toys_products WHERE product_code = ?");
    mysqli_stmt_bind_param($delVariants, 's', $code);
    mysqli_stmt_execute($delVariants);
    mysqli_stmt_close($delVariants);

    // If master table exists, delete from master too
    if (mysqli_num_rows(mysqli_query($conn, "SHOW TABLES LIKE 'toys_products_master'")) > 0) {
        $delMaster = mysqli_prepare($conn, "DELETE FROM toys_products_master WHERE product_code = ?");
        mysqli_stmt_bind_param($delMaster, 's', $code);
        mysqli_stmt_execute($delMaster);
        mysqli_stmt_close($delMaster);
    }

    // Redirect to avoid resubmission and add deleted flag
    header("Location: view_toys_products.php?deleted=1");
    exit;
}

// fetch product codes page-wise (distinct product_code)
$productCodes = [];
$codesSql = "SELECT DISTINCT product_code FROM toys_products ORDER BY product_code LIMIT ? OFFSET ?";
$codesStmt = mysqli_prepare($conn, $codesSql);
mysqli_stmt_bind_param($codesStmt, 'ii', $limit, $offset);
mysqli_stmt_execute($codesStmt);
mysqli_stmt_bind_result($codesStmt, $pc);
while (mysqli_stmt_fetch($codesStmt)) {
    $productCodes[] = $pc;
}
mysqli_stmt_close($codesStmt);

include("header.php");

// build products array only if we have some codes
$products = [];
if (!empty($productCodes)) {
    $escaped = array_map(function($c) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $c) . "'";
    }, $productCodes);
    $inClause = implode(",", $escaped);

    // Select primary images from master (m) and variant images from p (secondary)
    // Force collation equality on the join to avoid the collation mixing error.
    $sql = "
        SELECT p.id as pid, k.name as category_name, p.product_code, p.variation_code,
               p.product_name,
               -- variant images (secondary)
               p.image1 AS v_image1, p.image2 AS v_image2, p.image3 AS v_image3, p.image4 AS v_image4,
               p.attribute, p.inventory_count,
               -- master primary images (if exists)
               m.primary_image1 AS m_image1, m.primary_image2 AS m_image2, m.primary_image3 AS m_image3, m.primary_image4 AS m_image4,
               COALESCE(p.mrp_price, m.mrp_price) AS mrp_price,
               COALESCE(p.actual_price, m.actual_price) AS actual_price
        FROM toys_products p
        LEFT JOIN toy k ON p.variation_id = k.id
        LEFT JOIN toys_products_master m 
            ON p.product_code COLLATE utf8mb4_unicode_ci = m.product_code COLLATE utf8mb4_unicode_ci
        WHERE p.product_code IN ($inClause)
        ORDER BY p.product_code, p.variation_code
    ";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $pcode = $row['product_code'];

        // collect master primary images into an ordered array (1..4)
        if (!isset($products[$pcode])) {
            $primaryImages = [];
            if (!empty($row['m_image1'])) $primaryImages[0] = $row['m_image1'];
            if (!empty($row['m_image2'])) $primaryImages[1] = $row['m_image2'];
            if (!empty($row['m_image3'])) $primaryImages[2] = $row['m_image3'];
            if (!empty($row['m_image4'])) $primaryImages[3] = $row['m_image4'];

            // if master images are empty, fall back to first variant images (but still keep placeholders)
            if (empty($primaryImages)) {
                if (!empty($row['v_image1'])) $primaryImages[0] = $row['v_image1'];
                if (!empty($row['v_image2'])) $primaryImages[1] = $row['v_image2'];
                if (!empty($row['v_image3'])) $primaryImages[2] = $row['v_image3'];
                if (!empty($row['v_image4'])) $primaryImages[3] = $row['v_image4'];
            }

            $products[$pcode] = [
                'category_name' => $row['category_name'] ?? '',
                'product_name'  => $row['product_name'] ?? '',
                'primary_images'=> $primaryImages,          // primary images array (indexes 0..3)
                'mrp_price'     => $row['mrp_price'] ?? 0,
                'actual_price'  => $row['actual_price'] ?? 0,
                'variations'    => []
            ];
        }

        // collect variant (secondary) images for every variation row
        $variantImages = [];
        if (!empty($row['v_image1'])) $variantImages[] = $row['v_image1'];
        if (!empty($row['v_image2'])) $variantImages[] = $row['v_image2'];
        if (!empty($row['v_image3'])) $variantImages[] = $row['v_image3'];
        if (!empty($row['v_image4'])) $variantImages[] = $row['v_image4'];

        $products[$pcode]['variations'][] = [
            'variation_code'  => $row['variation_code'],
            'attribute'       => $row['attribute'],
            'inventory_count' => $row['inventory_count'],
            'variant_images'  => $variantImages
        ];
    }
}
?>

<div class="main-content app-content" style="background-color:rgb(42, 42, 44); min-height:100vh; color:#fff;">
  <div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold">Toy's</h2>

    <?php if (isset($_GET['deleted'])): ?>
      <div class="alert alert-success">Product deleted successfully.</div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
      <div class="alert alert-secondary">No products found.</div>
    <?php else: ?>
      <?php foreach ($products as $code => $prod): ?>
        <div class="card mb-4" style="background-color:#1e1e1e; border:1px solid #333; border-radius:12px;">
          <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#0d6efd; border-radius:12px 12px 0 0;">
            <div>
              <strong style="font-size:1.15rem;"><?= htmlspecialchars($prod['product_name']); ?></strong>
              <span class="badge ms-2"
                    style="background-color:#ffc107; color:#000; font-size:0.95rem; padding:6px 10px; border-radius:8px;">
                <?= htmlspecialchars($prod['category_name']); ?>
              </span>
            </div>
            <div>
              <a href="update_toys_product.php?product_code=<?= urlencode($code); ?>" class="btn btn-sm" style="background-color:#ffc107; color:#000;">
                <i class="bi bi-pencil-square"></i>
              </a>

              <a href="view_toys_products.php?code=<?= urlencode($code); ?>"`
                 class="btn btn-sm"
                 style="background-color:#dc3545; color:#fff;"
                 onclick="return confirm('Are you sure you want to delete this product and its variations?');">
                <i class="bi bi-trash"></i>
              </a>
            </div>
          </div>

          <div class="card-body">
            <!-- PRIMARY IMAGES (4 boxes) -->
            <div class="row mb-3">
              <?php for ($i = 0; $i < 4; $i++): 
                  $img = $prod['primary_images'][$i] ?? '';
              ?>
                <div class="col-md-3 col-6 mb-2 d-flex justify-content-center align-items-center">
                  <?php if ($img && file_exists($img)): ?>
                    <img src="<?= htmlspecialchars($img); ?>" class="img-fluid rounded border"
                         style="height:120px; width:120px; object-fit:cover; border:2px solid #444;">
                  <?php else: ?>
                    <div style="height:120px; width:120px; display:flex; align-items:center; justify-content:center; background:#2a2a2a; color:#bbb; border:2px dashed #444; border-radius:6px;">
                      Primary <?= ($i+1); ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endfor; ?>
            </div>

            <!-- Prices -->
            <div class="mb-3" style="color:#ffc107;">
              <strong>MRP:</strong> ₹<?= number_format($prod['mrp_price'], 2); ?> &nbsp; |
              &nbsp; <strong>Actual Price:</strong> ₹<?= number_format($prod['actual_price'], 2); ?>
            </div>

            <!-- Variations Table + secondary (variant) images -->
            <div class="table-responsive">
              <table class="table align-middle mb-0" style="background-color:#1a1a1a; color:#e0e0e0;">
                <thead>
                  <tr style="background-color:#262626; color:#f5f5f5;">
                    <th style="padding:12px;">Variation Code</th>
                    <th style="padding:12px;">Attribute</th>
                    <th style="padding:12px;">Inventory</th>
                    <th style="padding:12px;">Variant Images</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($prod['variations'] as $v): ?>
                    <tr style="transition: background-color 0.3s;"
                        onmouseover="this.style.backgroundColor='#2c2c2c';"
                        onmouseout="this.style.backgroundColor='transparent';">
                      <td style="border-top:1px solid #333; padding:12px;"><?= htmlspecialchars($v['variation_code']); ?></td>
                      <td style="border-top:1px solid #333; padding:12px;"><?= htmlspecialchars($v['attribute']); ?></td>
                      <td style="border-top:1px solid #333; padding:12px;"><?= htmlspecialchars($v['inventory_count']); ?></td>
                      <td style="border-top:1px solid #333; padding:12px;">
                        <div class="d-flex flex-row flex-wrap">
                          <?php if (!empty($v['variant_images'])): ?>
                            <?php foreach ($v['variant_images'] as $vi): ?>
                              <?php if ($vi && file_exists($vi)): ?>
                                <img src="<?= htmlspecialchars($vi); ?>" style="height:48px; width:48px; object-fit:cover; margin-right:6px; border-radius:4px; border:1px solid #444;">
                              <?php endif; ?>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <small class="text-muted">—</small>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      <?php endforeach; ?>

      <!-- PAGINATION -->
      <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
              <a class="page-link" href="view_toys_products.php?page=<?= $i; ?>"
                 style="background-color:#1e1e1e; color:#fff; border:1px solid #333;"><?= $i; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>

    <?php endif; ?>
  </div>
</div>

<?php include("footer.php"); ?>
