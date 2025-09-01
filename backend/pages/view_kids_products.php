<?php
include 'db.php';

$countRes = mysqli_query($conn, "SELECT COUNT(DISTINCT product_code) AS total FROM kids_products");
$rowCount = mysqli_fetch_assoc($countRes);
$totalProducts = $rowCount['total'];

$limit = 3; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$totalPages = ($totalProducts > 0) ? ceil($totalProducts / $limit) : 1;

$productCodes = [];
$codesRes = mysqli_query($conn, "SELECT DISTINCT product_code FROM kids_products ORDER BY product_code LIMIT $limit OFFSET $offset");
while($c = mysqli_fetch_assoc($codesRes)){
    $productCodes[] = "'" . mysqli_real_escape_string($conn, $c['product_code']) . "'";
}

if(isset($_GET['code'])){
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    mysqli_query($conn, "DELETE FROM kids_products WHERE product_code='$code'");
    header("Location: view_kids_products.php?deleted=1");
    exit;
}
include("header.php");

$products = [];
if(!empty($productCodes)){
    $inClause = implode(",", $productCodes);
   $sql = "
            SELECT p.id as pid, k.name as category_name, p.product_code, p.variation_code,
                   p.product_name, p.image1,p.image2,p.image3,p.image4,
                   p.attribute,p.inventory_count,p.mrp_price,p.actual_price
            FROM kids_products p
            LEFT JOIN kids k ON p.variation_id = k.id
            WHERE p.product_code IN ($inClause)
            ORDER BY p.product_code, p.variation_code
            ";

    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)){
        $pcode = $row['product_code'];
        if(!isset($products[$pcode])){
            $products[$pcode] = [
                'category_name' => $row['category_name'],
                'product_name'  => $row['product_name'],
                'images'        => [$row['image1'],$row['image2'],$row['image3'],$row['image4']],
                'mrp_price'     => $row['mrp_price'],
                'actual_price'  => $row['actual_price'],
                'variations'    => []
            ];
        }
        $products[$pcode]['variations'][] = [
            'variation_code' => $row['variation_code'],
            'attribute'      => $row['attribute'],
            'inventory_count'=> $row['inventory_count']
        ];
    }
}
?>

<div class="main-content app-content" style="background-color:rgb(42, 42, 44); min-height:100vh; color:#fff;">
<div class="container-fluid py-4">
  <h2 class="mb-4 fw-bold">Kid's</h2>

  <?php if(isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Product deleted successfully.</div>
  <?php endif; ?>

  <?php if(empty($products)): ?>
    <div class="alert alert-secondary">No products found.</div>
  <?php else: ?>
    <?php foreach($products as $code => $prod): ?>
      <div class="card mb-4" style="background-color:#1e1e1e; border:1px solid #333; border-radius:30px;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#0d6efd;">
          <div>
            <strong style="font-size:1.3rem;"><?= htmlspecialchars($prod['product_name']); ?></strong>
            <span class="badge ms-2" 
                  style="background-color:#ffc107; color:#000; font-size:1rem; padding:8px 12px; border-radius:8px;">
              <?= htmlspecialchars($prod['category_name']); ?>
            </span>
          </div>
          <div>
           <a href="update_kids_product.php?code=<?= urlencode($code); ?>" class="btn btn-sm" style="background-color:#ffc107; color:#000;">
                <i class="bi bi-pencil-square"></i>
            </a>

            <a href="view_kids_products.php?code=<?= urlencode($code); ?>" 
               class="btn btn-sm" style="background-color:#dc3545; color:#fff;"
               onclick="return confirm('Are you sure you want to delete this product and its variations?');">
              <i class="bi bi-trash"></i>
            </a>
          </div>
        </div>
        <div class="card-body">
          <!-- Images -->
          <div class="row mb-3">
            <?php foreach($prod['images'] as $img): ?>
              <?php if($img): ?>
                <div class="col-md-3 col-6 mb-2">
                  <img src="<?= htmlspecialchars($img); ?>" 
                       class="img-fluid rounded border"
                       style="height:140px; width:140px; object-fit:cover; border:2px solid #444;">
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <!-- Prices -->
          <div class="mb-3" style="color:#ffc107;">
            <strong>MRP:</strong> ₹<?= number_format($prod['mrp_price'],2); ?> |
            <strong>Actual Price:</strong> ₹<?= number_format($prod['actual_price'],2); ?>
          </div>

          <!-- Variations Table -->
          <div class="table-responsive">
            <table class="table align-middle mb-0" style="background-color:#1a1a1a; color:#e0e0e0; border-collapse:separate; border-spacing:0;">
              <thead>
                <tr style="background-color:#262626; color:#f5f5f5;">
                  <th style="border:none; padding:12px;">Variation Code</th>
                  <th style="border:none; padding:12px;">Attribute</th>
                  <th style="border:none; padding:12px;">Inventory</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($prod['variations'] as $v): ?>
                  <tr style="transition: background-color 0.3s;"
                      onmouseover="this.style.backgroundColor='#2c2c2c';"
                      onmouseout="this.style.backgroundColor='transparent';">
                    <td style="border-top:1px solid #333; padding:12px;"><?= htmlspecialchars($v['variation_code']); ?></td>
                    <td style="border-top:1px solid #333; padding:12px;"><?= htmlspecialchars($v['attribute']); ?></td>
                    <td style="border-top:1px solid #333; padding:12px;"><?= htmlspecialchars($v['inventory_count']); ?></td>
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
        <?php for($i=1; $i<=$totalPages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
            <a class="page-link" href="view_kids_products.php?page=<?= $i; ?>" 
               style="background-color:#1e1e1e; color:#fff; border:1px solid #333;"><?= $i; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>

  <?php endif; ?>
</div>
</div>

<?php include("footer.php"); ?>
