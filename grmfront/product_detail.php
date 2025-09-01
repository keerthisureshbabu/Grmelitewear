<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../backend/pages/db.php'; // adjust path if needed

// escape helper
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function fetch_rep_variation(mysqli $conn, string $table, string $product_code): ?array {
    $code = $conn->real_escape_string($product_code);
    $sql = "SELECT id, variation_code, attribute, actual_price, mrp_price, image1, inventory_count
            FROM `$table`
            WHERE product_code = '$code'
            ORDER BY CASE WHEN (inventory_count > 0) THEN 0 ELSE 1 END, id
            LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows) return $res->fetch_assoc();

    $sql2 = "SELECT id, variation_code, attribute, actual_price, mrp_price, image1, inventory_count
             FROM `$table`
             WHERE product_code = '$code'
             LIMIT 1";
    $res2 = $conn->query($sql2);
    if ($res2 && $res2->num_rows) return $res2->fetch_assoc();
    return null;
}

/* ---------- Inputs & table map ---------- */
$category = $_GET['type'] ?? '';
$product_code = $_GET['product_code'] ?? '';

$productTables = [
    'kids' => 'kids_products',
    'women' => 'womens_products',
    'toy' => 'toys_products',
    'accessories' => 'accessories_products'
];

if (!isset($productTables[$category]) || !$product_code) {
    echo "<div class='container py-80 text-center'><h4>Invalid product or category</h4><a href='index.php' class='btn btn-main mt-16'>Back</a></div>";
    exit;
}

$productTable = $productTables[$category];
$escaped_code = $conn->real_escape_string($product_code);

/* ---------- Fetch all variations for this product ---------- */
$sql = "SELECT * FROM `$productTable` WHERE product_code = ? ORDER BY id";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "DB error: " . h($conn->error);
    exit;
}
$stmt->bind_param('s', $product_code);
$stmt->execute();
$res = $stmt->get_result();
$variations = [];
while ($row = $res->fetch_assoc()) $variations[] = $row;
$stmt->close();

if (empty($variations)) {
    echo "<div class='container py-80 text-center'><h4>Product not found</h4><a href='index.php' class='btn btn-main mt-16'>Back</a></div>";
    exit;
}

/* choose default variation - prefer first in-stock */
$defaultVar = null;
foreach ($variations as $v) {
    if ((int)($v['inventory_count'] ?? 0) > 0) { $defaultVar = $v; break; }
}
if (!$defaultVar) $defaultVar = $variations[0];

$initialStock = (int)($defaultVar['inventory_count'] ?? 0);
$initialQty = ($initialStock > 0) ? 1 : 0;

/* ---------- Build related products (up to 4) ---------- */
$related = [];
$relatedMap = [];
$allTables = array_values($productTables);
$variationTables = [$productTable];
foreach ($allTables as $t) if (!in_array($t, $variationTables, true)) $variationTables[] = $t;

foreach ($variationTables as $vt) {
    if (count($related) >= 4) break;
    $limit = 4 - count($related);
    $sql2 = "SELECT product_code, product_name, image1 FROM `$vt` WHERE product_code != ? GROUP BY product_code LIMIT $limit";
    if ($st = $conn->prepare($sql2)) {
        $st->bind_param('s', $product_code);
        $st->execute();
        $rres = $st->get_result();
        while ($row = $rres->fetch_assoc()) {
            $code = $row['product_code'] ?? '';
            if (!$code || isset($relatedMap[$code])) continue;
            $row['source_table'] = $vt;
            $relatedMap[$code] = true;
            $related[] = $row;
            if (count($related) >= 4) break;
        }
        $st->close();
    }
}

/* ---------- Output (HTML) ---------- */
$imgBase = '../backend/pages/'; // change if your images live elsewhere
include("header.php");
?>

<div class="breadcrumb mb-0 py-26 bg-main-two-50">
  <div class="container container-lg">
    <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
      <h6 class="mb-0">Product Details</h6>
      <ul class="flex-align gap-8 flex-wrap">
        <li class="text-sm"><a href="index.php" class="text-gray-900 flex-align gap-8 hover-text-main-600"><i class="ph ph-house"></i> Home</a></li>
        <li class="flex-align"><i class="ph ph-caret-right"></i></li>
        <li class="text-sm text-main-600"><?= h(ucfirst($category)) ?></li>
      </ul>
    </div>
  </div>
</div>

<section class="product-details py-80">
  <div class="container container-lg">
    <div class="row gy-4">
      <div class="col-xl-9">
        <div class="row gy-4">
          <!-- Images -->
          <div class="col-xl-6">
            <div class="product-details__left">
              <div class="product-details__thumb-slider border border-gray-100 rounded-16">
                <?php for ($i=1;$i<=4;$i++):
                  $imgKey = "image{$i}";
                  if (!empty($defaultVar[$imgKey]) || !empty($variations[0][$imgKey])):
                      $imgPath = $defaultVar[$imgKey] ?? $variations[0][$imgKey];
                      if ($imgPath && !preg_match('#^https?://#i', $imgPath)) $imgPath = $imgBase . ltrim($imgPath, '/');
                ?>
                <div class="product-details__thumb flex-center h-100">
                  <img src="<?= h($imgPath) ?>" alt="Image <?= $i ?>">
                </div>
                <?php endif; endfor; ?>
              </div>

              <div class="mt-24 product-details__images-slider">
                <?php for ($i=1;$i<=4;$i++):
                  $imgKey = "image{$i}";
                  if (!empty($defaultVar[$imgKey]) || !empty($variations[0][$imgKey])):
                      $imgPath = $defaultVar[$imgKey] ?? $variations[0][$imgKey];
                      if ($imgPath && !preg_match('#^https?://#i', $imgPath)) $imgPath = $imgBase . ltrim($imgPath, '/');
                ?>
                <div>
                  <div class="max-w-130 max-h-150 h-100 flex-center border border-gray-100 rounded-16 p-8">
                    <img src="<?= h($imgPath) ?>" alt="Thumb <?= $i ?>">
                  </div>
                </div>
                <?php endif; endfor; ?>
              </div>
            </div>
          </div>

          <!-- Product Info -->
          <div class="col-xl-6">
            <div class="product-details__content">
              <h5 class="mb-12"><?= h($variations[0]['product_name'] ?? '') ?></h5>
              <div class="flex-align flex-wrap gap-12">
                <span class="text-sm fw-medium text-gray-500">|</span>
                <span class="text-gray-900"><span class="text-gray-400">SKU:</span> <span id="sku-display"><?= h($defaultVar['variation_code'] ?? '') ?></span></span>
              </div>

              <div class="my-32 flex-align gap-16 flex-wrap">
                <div class="flex-align gap-8">
                  <h6 class="mb-0 text-main-600">₹<?= h($defaultVar['actual_price'] ?? $variations[0]['actual_price'] ?? '0') ?>.00</h6>
                </div>
                <div class="flex-align gap-8">
                  <span class="text-gray-700">MRP</span>
                  <h6 class="text-xl text-gray-400 mb-0 fw-medium"><del>₹<?= h($defaultVar['mrp_price'] ?? $variations[0]['mrp_price'] ?? '0') ?>.00</del></h6>
                </div>
              </div>

              <!-- Product Form -->
              <form id="productForm" action="add_to_cart.php" method="POST">
                <div class="mb-32">
                  <label class="h6 activePage mb-8 text-heading fw-semibold d-block">Size</label>
                  <div class="flex gap-8">
                    <?php foreach ($variations as $v):
                        $stock = (int)($v['inventory_count'] ?? 0);
                        $isDisabled = $stock <= 0;
                        $checked = ($v['id'] == $defaultVar['id']) ? 'checked' : '';
                    ?>
                      <label class="btn btn-outline-main variation-label <?= $isDisabled ? 'variation-disabled' : '' ?>"
                             style="border-color:#ac5393;color:#ac5393;padding:8px 16px;cursor:<?= $isDisabled ? 'not-allowed' : 'pointer' ?>;"
                             data-sku="<?= h($v['variation_code'] ?? '') ?>"
                             data-stock="<?= h($stock) ?>">
                        <input type="radio" name="variation_id" value="<?= h($v['id']) ?>" <?= $checked ?> <?= $isDisabled ? 'disabled' : '' ?> hidden required>
                        <?= h($v['attribute'] ?? $v['variation_code'] ?? 'Variant') ?>
                        <?php if ($isDisabled): ?><small class="d-block text-sm text-danger">Out of stock</small><?php endif; ?>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- Stock & Quantity -->
                <div class="mb-32">
                  <label class="text-lg mb-8 text-heading fw-semibold d-block">
                    Total Stock: <span id="stock-display"><?= h($initialStock) ?></span>
                    <span id="stock-status" class="text-sm ml-8"><?= ($initialStock>0? '' : 'Out of stock') ?></span>
                  </label>

                  <div class="d-flex rounded-4 overflow-hidden align-items-center quantity-container" style="max-width:200px;">
                    <button type="button" id="qtyMinus" class="quantity__minus h-48 w-48 text-neutral-600 bg-gray-50 flex-center" aria-label="Decrease quantity">-</button>
                    <input id="qtyInput" type="number" name="qty" class="quantity__input" value="<?= h($initialQty) ?>" min="<?= ($initialStock>0?1:0) ?>" step="1" max="<?= h($initialStock) ?>" style="width:60px;text-align:center;">
                    <button type="button" id="qtyPlus" class="quantity__plus h-48 w-48 text-neutral-600 bg-gray-50 flex-center" aria-label="Increase quantity">+</button>
                  </div>
                </div>

                <input type="hidden" name="product_code" value="<?= h($product_code) ?>">
                <input type="hidden" name="category" value="<?= h($category) ?>">

                <button type="submit" id="addToCartBtn" class="btn btn-main flex-center gap-8 rounded-8 py-16 fw-normal mt-48" <?= ($initialStock<=0)?'disabled':'' ?>>
                  <i class="ph ph-shopping-cart-simple text-lg"></i> Add To Cart
                </button>

                <button type="submit" formaction="checkout.php" id="buyNowBtn" class="btn btn-outline-main rounded-8 py-16 fw-normal mt-16 w-100" <?= ($initialStock<=0)?'disabled':'' ?>>
                  Buy Now
                </button>
              </form>

              <br><br>
              <a href="https://wa.me/919003351632?text=I'm%20interested%20in%20<?= urlencode($variations[0]['product_name'] ?? '') ?>" target="_blank" class="btn btn-black flex-center gap-8 rounded-8 py-16">
                <i class="ph ph-whatsapp-logo text-lg"></i> Request More Information
              </a>

              <div class="mt-32">
                <span class="fw-medium text-gray-900">100% Guarantee Safe Checkout</span>
                <div class="mt-10">
                  <img src="assets/images/thumbs/gateway-img.png" alt="Payment Gateways">
                </div>
              </div>
            </div>
          </div>
        </div> <!-- /row -->

        <!-- Related Products -->

        <?php if (!empty($related)): ?>
        <div class="row mt-24">
          <div class="col-12"><h5 class="mb-20">Related Products</h5></div>
          <?php foreach ($related as $rel):
              $rel_code = $rel['product_code'];
              $rel_name = $rel['product_name'] ?? '';
              $source = $rel['source_table'] ?? $productTable;
              $rel_var = fetch_rep_variation($conn, $source, $rel_code) ?: ['variation_code'=>'','attribute'=>'','actual_price'=>null,'mrp_price'=>null,'image1'=>$rel['image1'] ?? '','inventory_count'=>0,'id'=>null];
              $rel_img = $rel_var['image1'] ?: ($rel['image1'] ?? '');
              if ($rel_img && !preg_match('#^https?://#i', $rel_img)) $rel_img = $imgBase . ltrim($rel_img, '/');
              $rel_img = $rel_img ?: 'assets/images/no-image.png';
              $price = $rel_var['actual_price'];
              $mrp = $rel_var['mrp_price'];

              // determine category key (kids,women,toy,accessories) from the source table name
              $catKey = array_search($source, $productTables, true);
              $categorytype = ($catKey !== false) ? $catKey : $category;
          ?>
            <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="200">
              <div class="product-card p-16 border border-gray-100 hover-border-main-600 rounded-16 bg-white transition-2">
                <div class="product-card__thumb">
                  <a href="<?= h("product_detail.php?type=".urlencode($categorytype)."&product_code=".urlencode($rel_code)) ?>">
                    <img src="<?= h($rel_img) ?>" alt="<?= h($rel_name) ?>">
                  </a>
                </div>
                <div class="product-card__content">
                  <h6 class="title text-lg fw-semibold mt-12 mb-8">
                    <a href="<?= h("product_detail.php?type=".urlencode($categorytype)."&product_code=".urlencode($rel_code)) ?>" class="link text-line-2"><?= h($rel_name) ?></a>
                  </h6>
                  <?php if (!empty($rel_var['variation_code'])): ?>
                    <p class="small text-muted mb-2">Variation: <?= h($rel_var['variation_code']) ?><?= !empty($rel_var['attribute']) ? ' ('.h($rel_var['attribute']).')' : '' ?></p>
                  <?php endif; ?>
                  <div class="product-card__price mb-12">
                    <?php if ($mrp !== null && $mrp !== '' && (float)$mrp > (float)$price): ?>
                      <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">₹<?= h($mrp) ?></span>
                    <?php endif; ?>
                    <?php if ($price !== null && $price !== ''): ?>
                      <span class="text-heading text-md fw-semibold">₹<?= h($price) ?><span class="text-gray-500 fw-normal">/Qty</span></span>
                    <?php endif; ?>
                  </div>
                  <form method="GET" action="<?= h("product_detail.php") ?>">
                    <input type="hidden" name="type" value="<?= h($category) ?>">
                    <input type="hidden" name="product_code" value="<?= h($rel_code) ?>">
                    <button type="submit" class="btn bg-gray-50 text-heading hover-bg-main-600 hover-text-white w-100 py-10 rounded-8 fw-medium">Quick View <i class="ph ph-eye ms-1"></i></button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<style>
label.active { background-color:#ac5393 !important; color:#fff !important; border-color:#ac5393 !important; }
.quantity__input { background:#fff !important; color:#000 !important; font-size:16px; height:48px; padding:0 8px !important; width:40px !important; border:1px solid #ccc; text-align:center; }
.variation-disabled { opacity:0.45; cursor:not-allowed; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const skuDisplay = document.getElementById('sku-display');
  const stockDisplay = document.getElementById('stock-display');
  const stockStatus = document.getElementById('stock-status');
  const qtyInput = document.getElementById('qtyInput');
  const minusBtn = document.getElementById('qtyMinus');
  const plusBtn = document.getElementById('qtyPlus');
  const addToCartBtn = document.getElementById('addToCartBtn');
  const buyNowBtn = document.getElementById('buyNowBtn');

  function updateQtyButtons() {
    const cur = parseInt(qtyInput.value) || 0;
    const min = parseInt(qtyInput.getAttribute('min')) || 0;
    const max = parseInt(qtyInput.getAttribute('max')) || 0;

    minusBtn.disabled = (cur <= min);
    plusBtn.disabled  = (cur >= max);
  }

  function applyVariationState(sku, stock) {
    skuDisplay.textContent = sku || '';
    stockDisplay.textContent = stock;
    const stockNum = parseInt(stock) || 0;

    if (stockNum <= 0) {
      stockStatus.textContent = 'Out of stock';
      qtyInput.value = 0;
      qtyInput.setAttribute('min', 0);
      qtyInput.setAttribute('max', 0);
      addToCartBtn.disabled = true;
      buyNowBtn.disabled = true;
    } else {
      stockStatus.textContent = '';
      qtyInput.value = 1;
      qtyInput.setAttribute('min', 1);
      qtyInput.setAttribute('max', stockNum);
      addToCartBtn.disabled = false;
      buyNowBtn.disabled = false;
    }
    updateQtyButtons();
  }

  // qty minus
  minusBtn.addEventListener('click', () => {
    let cur = parseInt(qtyInput.value) || 1;
    if (cur > 1) qtyInput.value = cur - 1;
    updateQtyButtons();
  });

  // qty plus
  plusBtn.addEventListener('click', () => {
    let cur = parseInt(qtyInput.value) || 1;
    const max = parseInt(qtyInput.getAttribute('max')) || 1;
    if (cur < max) qtyInput.value = cur + 1;
    updateQtyButtons();
  });

  // manual input
  qtyInput.addEventListener('input', () => {
    let val = parseInt(qtyInput.value);
    const min = parseInt(qtyInput.getAttribute('min')) || 1;
    const max = parseInt(qtyInput.getAttribute('max')) || 1;
    if (isNaN(val) || val < min) val = min;
    if (val > max) val = max;
    qtyInput.value = val;
    updateQtyButtons();
  });

  // initial setup
  let initialRadio = document.querySelector('input[name="variation_id"]:checked');
  if (initialRadio) {
    const lab = initialRadio.closest('label');
    const sku = lab?.getAttribute('data-sku') || '';
    const stock = lab?.getAttribute('data-stock') || 0;
    applyVariationState(sku, stock);
  }

  // reapply on variation change
  document.querySelectorAll('input[name="variation_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
      const lab = this.closest('label');
      const sku = lab?.getAttribute('data-sku') || '';
      const stock = lab?.getAttribute('data-stock') || 0;
      applyVariationState(sku, stock);
    });
  });
});

</script>

<?php include("footer.php"); ?>
