<?php 
include("header.php");
include('../backend/pages/db.php');

function h($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

// Inputs
$product_code = isset($_GET['product_code']) ? trim($_GET['product_code']) : '';
$variation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// validate presence
if (empty($product_code) && $variation_id <= 0) {
    echo "<div class='container py-80'><p>Invalid product. No product identifier provided.</p></div>";
    include("footer.php");
    exit;
}

// if only id provided, resolve product_code
if (empty($product_code) && $variation_id > 0) {
    $stmt = $conn->prepare("SELECT product_code FROM toys_products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $variation_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($r = $res->fetch_assoc()) {
        $product_code = $r['product_code'];
    }
    $stmt->close();
    if (empty($product_code)) {
        echo "<div class='container py-80'><p>Product not found.</p></div>";
        include("footer.php");
        exit;
    }
}

// optional master table fetch
$masterExists = false;
$masterTable = "toys_products_master";
$checkMaster = $conn->query("SHOW TABLES LIKE '". $conn->real_escape_string($masterTable) ."'");
if ($checkMaster && $checkMaster->num_rows > 0) {
    $mstmt = $conn->prepare("SELECT * FROM $masterTable WHERE product_code = ? LIMIT 1");
    $mstmt->bind_param("s", $product_code);
    $mstmt->execute();
    $mres = $mstmt->get_result();
    if ($mrow = $mres->fetch_assoc()) {
        $master = $mrow;
        $masterExists = true;
    }
    $mstmt->close();
}

// fetch variations
$pstmt = $conn->prepare("SELECT * FROM toys_products WHERE product_code = ? ORDER BY id ASC");
$pstmt->bind_param("s", $product_code);
$pstmt->execute();
$presult = $pstmt->get_result();
$variations = [];
while ($prow = $presult->fetch_assoc()) {
    // collect images (support image1..image4, fallback to image_1..image_4 if you have those names)
    $images = [];
    $cols = ['image1','image2','image3','image4','image_1','image_2','image_3','image_4'];
    foreach ($cols as $col) {
        if (!empty($prow[$col])) {
            $val = trim($prow[$col]);
            if ($val !== '') $images[] = $val;
        }
    }
    $prow['images'] = $images;
    if (!isset($prow['inventory_count'])) {
        $prow['inventory_count'] = $prow['stock'] ?? 0;
    }
    $variations[] = $prow;
}
$pstmt->close();

if (empty($variations)) {
    echo "<div class='container py-80'><p>No variations found for this product.</p></div>";
    include("footer.php");
    exit;
}

// decide default variation index
$defaultIndex = 0;
if ($variation_id > 0) {
    foreach ($variations as $i => $v) {
        if ((int)$v['id'] === $variation_id) { $defaultIndex = $i; break; }
    }
}
$defaultVar = $variations[$defaultIndex];

// helper: looks like color
function looks_like_color($attr){
    if ($attr === null) return false;
    $a = trim($attr);
    if ($a === '') return false;
    if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $a)) return true;
    $common = ['red','blue','green','black','white','pink','yellow','orange','purple','brown','gray','grey','maroon','olive','navy','teal','magenta','violet','beige','gold','silver'];
    foreach ($common as $c) if (stripos($a, $c) !== false) return true;
    if (preg_match('/^[A-Za-z ]{1,20}$/', $a) && strlen($a) <= 20 && str_word_count($a) <= 3) return true;
    return false;
}

// detect UI type
$colorCount = 0;
foreach ($variations as $v) if (!empty($v['attribute']) && looks_like_color($v['attribute'])) $colorCount++;
$useColorUI = ($colorCount >= 1);

// normalize images to full (relative) URLs for JS
$imgBase = '../backend/pages/';
function normalize_images($imgArray, $imgBase) {
    $out = [];
    if (!is_array($imgArray)) return $out;
    foreach ($imgArray as $im) {
        if (!$im) continue;
        if (preg_match('#^https?://#i', $im) || strpos($im, $imgBase) === 0) {
            $out[] = $im;
        } else {
            $out[] = $imgBase . ltrim($im, '/');
        }
    }
    return $out;
}

$normalized_variations = [];
foreach ($variations as $v) {
    $nv = $v;
    $nv['images_full'] = normalize_images($v['images'], $imgBase);
    $nv['stock_normalized'] = isset($v['inventory_count']) ? (int)$v['inventory_count'] : (isset($v['stock']) ? (int)$v['stock'] : 0);
    $normalized_variations[] = $nv;
}

// produce JS-safe variations (images are full urls)
$js_variations = [];
foreach ($normalized_variations as $v){
    $js_variations[] = [
        'id' => (int)$v['id'],
        'variation_code' => $v['variation_code'],
        'attribute' => $v['attribute'],
        'images' => $v['images_full'],
        'mrp_price' => $v['mrp_price'],
        'actual_price' => $v['actual_price'],
        'stock' => $v['stock_normalized'],
    ];
}

$product_title = $masterExists && !empty($master['product_name']) ? $master['product_name'] : $defaultVar['product_name'];
$product_desc = $masterExists && !empty($master['description']) ? $master['description'] : ($defaultVar['description'] ?? '');
$category = 'toy';
// Related-products category override via ?cat=
$catParam = isset($_GET['cat']) ? strtolower(trim($_GET['cat'])) : '';
$relCategory = in_array($catParam, ['kids','women','womens','toys','accessories']) ? $catParam : 'toys';
if ($relCategory === 'women') $relCategory = 'womens';
?>
<div class="breadcrumb mb-0 py-26 bg-main-two-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Product Details</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm"><a href="index.php" class="text-gray-900">Home</a></li>
                <li class="flex-align"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm text-main-600">Toys</li>
            </ul>
        </div>
    </div>
</div>

<section class="product-details py-80">
    <div class="container container-lg">
        <div class="row g-4">
            <!-- Gallery -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <div class="product-details__thumb-slider border border-gray-100 rounded-16">
                        <?php
                        $initial = $normalized_variations[$defaultIndex]['images_full'][0] ?? '';
                        $initial = $initial ?: 'placeholder.png';
                        ?>
                        <img id="mainImage" class="product-details__thumb" src="<?= h($initial) ?>" alt="<?= h($product_title) ?>">
                    </div>

                    <div class="mt-24 product-details__images-slider d-flex gap-8" id="thumbnails">
                        <?php
                        $thumbs = $normalized_variations[$defaultIndex]['images_full'] ?? [];
                        if (empty($thumbs)) {
                            echo '<div class="thumb"><img src="placeholder.png" alt="thumb"></div>';
                        } else {
                            foreach ($thumbs as $idx => $t): ?>
                                <div class="thumb" role="button" tabindex="0" data-src="<?= h($t) ?>">
                                    <img src="<?= h($t) ?>" alt="thumb <?= ($idx+1) ?>">
                                </div>
                            <?php endforeach;
                        }
                        ?>
                    </div>
                    <div id="variationGalleries" style="display:none;"></div>
                </div>
            </div>

            <!-- Info -->
            <div class="col-xl-6">
                <div class="product-details__content">
                    <h5 class="mb-12"><?= h($product_title) ?></h5>
                    <div class="flex-align flex-wrap gap-12 mb-12">
                        <span class="text-gray-900">
                            <span class="text-gray-400">SKU:</span> <span id="sku-display"><?= h($defaultVar['variation_code']) ?></span>
                        </span>
                    </div>

                    <div class="my-24 flex-align gap-16 flex-wrap">
                        <div>
                            <h6 class="mb-0 text-main-600">₹<span id="price-display"><?= h($defaultVar['actual_price']) ?></span>.00</h6>
                        </div>
                        <div>
                            <h6 class="text-xl text-gray-400 mb-0 fw-medium"><del>₹<?= h($defaultVar['mrp_price']) ?>.00</del></h6>
                        </div>
                    </div>

                    <form action="add_to_cart.php" method="POST" id="productForm">
                        <div class="mb-24">
                            <label class="h6 mb-8 text-heading fw-semibold d-block" id="attr-label"><?= $useColorUI ? 'Color' : 'Size' ?></label>
                            <div class="flex gap-8 align-items-center" id="attribute-options">
                                <?php if ($useColorUI): ?>
                                    <?php foreach ($normalized_variations as $v):
                                        $attr = $v['attribute'] ?? '';
                                        $isHex = preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', trim($attr));
                                        $displayColor = $isHex ? trim($attr) : strtolower(trim($attr));
                                        $data = json_encode([
                                            'id' => (int)$v['id'],
                                            'variation_code' => $v['variation_code'],
                                            'attribute' => $v['attribute'],
                                            'images' => $v['images_full'],
                                            'mrp_price' => $v['mrp_price'],
                                            'actual_price' => $v['actual_price'],
                                            'stock' => $v['stock_normalized']
                                        ], JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG);
                                        ?>
                                        <label style="cursor:pointer;" class="<?= ($v['id'] == $defaultVar['id']) ? 'active' : '' ?>">
                                            <input type="radio" name="variation_id" value="<?= (int)$v['id'] ?>" hidden <?= ($v['id'] == $defaultVar['id']) ? 'checked' : '' ?>>
                                            <!-- changed <button> to <span> to avoid default button backgrounds -->
                                            <span class="swatch-btn" data-var='<?= h($data) ?>' aria-label="<?= h($v['attribute']) ?>" title="<?= h($v['attribute']) ?>" style="border:1px solid #ddd;width:42px;height:42px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-right:8px;cursor:pointer">
                                                <span style="width:26px;height:26px;border-radius:50%;display:block;background:<?= h($displayColor) ?>;border:1px solid rgba(0,0,0,0.06)"></span>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php foreach ($normalized_variations as $v):
                                        $data = json_encode([
                                            'id' => (int)$v['id'],
                                            'variation_code' => $v['variation_code'],
                                            'attribute' => $v['attribute'],
                                            'images' => $v['images_full'],
                                            'mrp_price' => $v['mrp_price'],
                                            'actual_price' => $v['actual_price'],
                                            'stock' => $v['stock_normalized']
                                        ], JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG);
                                        ?>
                                        <label class="btn btn-outline-main <?= ($v['id'] == $defaultVar['id']) ? 'active' : '' ?>" style="padding:8px 12px;cursor:pointer;margin-right:6px;">
                                            <input type="radio" name="variation_id" value="<?= (int)$v['id'] ?>" hidden <?= ($v['id'] == $defaultVar['id']) ? 'checked' : '' ?>>
                                            <!-- changed <button> to <span> -->
                                            <span class="size-btn" data-var='<?= h($data) ?>' style="background:transparent;border:none;cursor:pointer;padding:6px 10px;display:inline-block;">
                                                <?= h($v['attribute']) ?>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-24">
                            <label class="text-lg mb-8 text-heading fw-semibold d-block">
                                Total Stock: <span id="stock-display"><?= h($defaultVar['inventory_count'] ?? $defaultVar['stock'] ?? 0) ?></span>
                            </label>

                            <div class="d-flex rounded-4 overflow-hidden align-items-center quantity-container" style="max-width:200px;">
                                <button type="button" class="quantity__minus h-48 w-48 text-neutral-600 bg-gray-50 flex-center" aria-label="Decrease quantity">-</button>
                                <?php
                                  $initialStock = (int)($defaultVar['inventory_count'] ?? $defaultVar['stock'] ?? 0);
                                  $initialQty = ($initialStock > 0) ? 1 : 0;
                                ?>
                                <input id="qtyInput" type="number" name="qty" class="quantity__input" value="<?= $initialQty ?>" min="<?= ($initialStock > 0) ? 1 : 0 ?>" step="1" max="<?= $initialStock ?>" style="width:60px;text-align:center;">
                                <button type="button" class="quantity__plus h-48 w-48 text-neutral-600 bg-gray-50 flex-center" aria-label="Increase quantity">+</button>
                            </div>
                        </div>

                        <input type="hidden" name="product_code" value="<?= h($product_code) ?>">
                        <input type="hidden" name="category" value="<?= h($category) ?>">

                        <div class="d-flex gap-8">
                            <button type="submit" class="btn btn-main flex-center gap-8 rounded-8 py-12 fw-normal">
                                <i class="ph ph-shopping-cart-simple text-lg"></i> Add To Cart
                            </button>
                        </div>

                        <button type="submit" formaction="buy.php" class="btn btn-outline-main rounded-8 py-16 fw-normal mt-16 w-100">Buy Now</button><br><br>

                        <a href="https://wa.me/91XXXXXXXXXX?text=I'm%20interested%20in%20<?= urlencode($product_title) ?>" target="_blank" class="btn btn-black flex-center gap-8 rounded-8 py-16">
                            <i class="ph ph-whatsapp-logo text-lg"></i> Request More Information
                        </a>

                        <div class="mt-32">
                            <span class="fw-medium text-gray-900">100% Guarantee Safe Checkout</span>
                            <div class="mt-10">
                                <img src="assets/images/thumbs/gateway-img.png" alt="Payment Gateways">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <br><br><br><br>

        <!-- Related Products: show up to 4 cards with image, variation snippet and pricing -->
        <?php
        // Build at least 4 related products from multiple tables
        $related = [];
        $relatedMap = [];

        // Determine preferred tables based on cat param
        $tableMap = [
            'kids' => ['table' => 'kids_products', 'master' => 'kids_products_master'],
            'womens' => ['table' => 'womens_products', 'master' => 'womens_products_master'],
            'toys' => ['table' => 'toys_products', 'master' => 'toys_products_master'],
            'accessories' => ['table' => 'accessories_products', 'master' => 'accessories_products_master'],
        ];
        $preferred = $tableMap[$relCategory] ?? $tableMap['toys'];

        // 1) Prefer category's master table when exists (different table)
        $preferredMasterExists = false;
        $pmCheck = $conn->query("SHOW TABLES LIKE '".$conn->real_escape_string($preferred['master'])."'");
        if ($pmCheck && $pmCheck->num_rows > 0) { $preferredMasterExists = true; }

        if ($preferredMasterExists) {
            $pm = $preferred['master'];
            $rstmt = $conn->prepare("SELECT product_code, product_name, primary_image1 AS image1 FROM $pm WHERE product_code != ? LIMIT 12");
            if ($rstmt) {
                $rstmt->bind_param("s", $product_code);
                $rstmt->execute();
                $rres = $rstmt->get_result();
                while ($row = $rres->fetch_assoc()) {
                    $code = $row['product_code'];
                    if (!$code || isset($relatedMap[$code])) continue;
                    $row['source_table'] = 'master';
                    $relatedMap[$code] = true;
                    $related[] = $row;
                    if (count($related) >= 4) break;
                }
                $rstmt->close();
            }
        }

        // 2) Fill from variations tables in priority order: preferred category first, then others
        $variationTables = [$preferred['table']];
        foreach (['toys_products'] as $vt) {
            if (!in_array($vt, $variationTables, true)) $variationTables[] = $vt;
        }
        foreach ($variationTables as $vt) {
            if (count($related) >= 4) break;
            $needed = 4 - count($related);
            $sql = "SELECT product_code, MIN(product_name) as product_name, MIN(image1) as image1 FROM $vt WHERE product_code != ? GROUP BY product_code LIMIT ?";
            $rs = $conn->prepare($sql);
            if ($rs) {
                $rs->bind_param("si", $product_code, $needed);
                $rs->execute();
                $rres = $rs->get_result();
                while ($row = $rres->fetch_assoc()) {
                    $code = $row['product_code'];
                    if (!$code || isset($relatedMap[$code])) continue;
                    $row['source_table'] = $vt;
                    $relatedMap[$code] = true;
                    $related[] = $row;
                    if (count($related) >= 4) break;
                }
                $rs->close();
            }
        }

        // helper: fetch one representative variation (cheapest) for each product_code from its source table
        function fetch_rep_variation(mysqli $conn, string $table, string $code) {
            $sql = "SELECT variation_code, attribute, actual_price, mrp_price, image1 FROM $table WHERE product_code = ? ORDER BY CAST(actual_price AS DECIMAL(10,2)) ASC, id ASC LIMIT 1";
            $st = $conn->prepare($sql);
            if (!$st) return null;
            $st->bind_param('s', $code);
            $st->execute();
            $res = $st->get_result();
            $row = $res->fetch_assoc();
            $st->close();
            return $row ?: null;
        }
        ?>

        <?php if (!empty($related)): ?>
            <div class="row mt-24">
                <div class="col-12">
                    <h5 class="mb-20">Related Products</h5>
                </div>
                <?php foreach ($related as $rel):
                    $rel_code = $rel['product_code'];
                    $rel_name = $rel['product_name'] ?? '';
                    // representative variation from correct source table
                    $source = $rel['source_table'] ?? 'toys_products';
                    if ($source === 'master') { $source = 'toys_products'; }
                    $rel_var = fetch_rep_variation($conn, $source, $rel_code) ?: ['variation_code'=>'','attribute'=>'','actual_price'=>null,'mrp_price'=>null,'image1'=>$rel['image1'] ?? ''];
                    $rel_img = $rel_var['image1'] ?: ($rel['image1'] ?? '');
                    if ($rel_img && !preg_match('#^https?://#i', $rel_img)) $rel_img = $imgBase . ltrim($rel_img, '/');
                    $rel_img = $rel_img ?: 'placeholder.png';
                    $price = $rel_var['actual_price'];
                    $mrp = $rel_var['mrp_price'];
                    ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="200">
                        <div class="product-card p-16 border border-gray-100 hover-border-main-600 rounded-16 bg-white transition-2">
                            <div class="product-card__thumb">
                                <a href="toys_product_detail.php?product_code=<?= h($rel_code) ?>">
                                    <img src="<?= h($rel_img) ?>" alt="<?= h($rel_name) ?>">
                                </a>
                            </div>
                            <div class="product-card__content">
                                <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                    <a href="toys_product_detail.php?product_code=<?= h($rel_code) ?>" class="link text-line-2">
                                        <?= h($rel_name) ?>
                                    </a>
                                </h6>
                                <?php if (!empty($rel_var['variation_code'])): ?>
                                    <p class="small text-muted mb-2">
                                        Variation: <?= h($rel_var['variation_code']) ?><?= !empty($rel_var['attribute']) ? ' ('.h($rel_var['attribute']).')' : '' ?>
                                    </p>
                                <?php endif; ?>
                                <div class="product-card__price mb-12">
                                    <?php if ($mrp !== null && $mrp !== '' && (float)$mrp > (float)$price): ?>
                                        <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">₹<?= h($mrp) ?></span>
                                    <?php endif; ?>
                                    <?php if ($price !== null && $price !== ''): ?>
                                        <span class="text-heading text-md fw-semibold">₹<?= h($price) ?><span class="text-gray-500 fw-normal">/Qty</span></span>
                                    <?php endif; ?>
                                </div>
                                <form method="GET" action="toys_product_detail.php">
                                    <input type="hidden" name="product_code" value="<?= h($rel_code) ?>">
                                    <button type="submit" class="btn bg-gray-50 text-heading hover-bg-main-600 hover-text-white w-100 py-10 rounded-8 fw-medium">
                                        Quick View <i class="ph ph-eye ms-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
<script>
    const variations = <?= json_encode($js_variations, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?> || [];
    let selected = variations.find(v => v.id === <?= (int)$defaultVar['id'] ?>) || variations[0] || null;
</script>
<script src="assets/js/product.js"></script>

<?php include("footer.php"); ?>
