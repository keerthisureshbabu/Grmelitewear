<?php 
include("header.php");
include('../backend/pages/db.php');

// Section and category selection
$section = isset($_GET['type']) ? $_GET['type'] : 'women';
$categoryId = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

// Table mapping
$categoryTables = [
    'kids' => 'kids',
    'women' => 'women',
    'toy' => 'toy',
    'accessories' => 'accessories'
];
$productTables = [
    'kids' => 'kids_products',
    'women' => 'womens_products',
    'toy' => 'toys_products',
    'accessories' => 'accessories_products'
];

// Validate section
if (!array_key_exists($section, $categoryTables)) {
    die("Invalid section.");
}
$categoryTable = $categoryTables[$section];
$productTable = $productTables[$section];

// Fetch categories for sidebar
$categories = $conn->query("SELECT id, name FROM $categoryTable ORDER BY name");

// Fetch products grouped by product_code
$productSql = "SELECT * FROM $productTable";
if ($categoryId > 0) {
    $productSql .= " WHERE variation_id = $categoryId";
}
$productSql .= " GROUP BY product_code ORDER BY id DESC";
$products = $conn->query($productSql);
?>

<section class="shop py-80">
    <div class="container container-lg">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="shop-sidebar">
                    <button type="button" class="shop-sidebar__close d-lg-none d-flex w-32 h-32 flex-center border border-gray-100 rounded-circle hover-bg-main-600 position-absolute inset-inline-end-0 me-10 mt-8 hover-text-white hover-border-main-600">
                        <i class="ph ph-x"></i>
                    </button>
                    <div class="shop-sidebar__box border border-gray-100 rounded-8 p-32 mb-32">
                        <h6 class="text-xl border-bottom border-gray-100 pb-24 mb-24"><?= ucfirst(htmlspecialchars($section)); ?> Category</h6>
                        <ul class="max-h-540 overflow-y-auto scroll-sm">
                            <?php while($cat = $categories->fetch_assoc()): ?>
                                <li class="mb-24">
                                    <a href="shop.php?type=<?= urlencode($section) ?>&cat=<?= $cat['id']; ?>" class="text-gray-900 hover-text-main-600">
                                        <?= htmlspecialchars($cat['name']); ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <!-- Category Name Display -->
                <?php if ($categoryId > 0): ?>
                    <?php
                        $categoryStmt = $conn->prepare("SELECT name FROM $categoryTable WHERE id = ?");
                        if ($categoryStmt) {
                            $categoryStmt->bind_param("i", $categoryId);
                            $categoryStmt->execute();
                            $categoryResult = $categoryStmt->get_result();
                            if ($categoryRow = $categoryResult->fetch_assoc()) {
                                echo '<h6 class="mb-24">' . htmlspecialchars($categoryRow['name']) . '</h6>';
                            }
                            $categoryStmt->close();
                        }
                    ?>
                <?php endif; ?>
                
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-sale" role="tabpanel" aria-labelledby="pills-sale-tab" tabindex="0">
                        <div class="row g-4">
                            <?php while($row = $products->fetch_assoc()): ?>
                                <?php
                                    $code = $row['product_code'];
                                    $variationQuery = $conn->query("SELECT id, attribute FROM $productTable WHERE product_code = '". $conn->real_escape_string($code) ."'");
                                    $seenSizes = [];
                                    $uniqueVariations = [];
                                    while ($v = $variationQuery->fetch_assoc()) {
                                        if (!in_array($v['attribute'], $seenSizes)) {
                                            $seenSizes[] = $v['attribute'];
                                            $uniqueVariations[] = $v;
                                        }
                                    }
                                ?>
                                <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="200">
                                    <div class="product-card p-16 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2 bg-white">
                                        <div class="product-card__thumb">
                                            <a href="product-details.php?id=<?= $row['id']; ?>&type=<?= urlencode($section) ?>">
                                                <img src="../backend/pages/<?= $row['image1']; ?>" alt="<?= htmlspecialchars($row['product_name']); ?>">
                                            </a>
                                        </div>

                                        <div class="product-card__content">
                                            <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                                <a href="product-details.php?id=<?= $row['id']; ?>&type=<?= urlencode($section) ?>" class="link text-line-2"><?= htmlspecialchars($row['product_name']); ?></a>
                                            </h6>

                                            <div class="product-card__price mb-12">
                                                <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">₹<?= htmlspecialchars($row['mrp_price']); ?></span>
                                                <span class="text-heading text-md fw-semibold">₹<?= htmlspecialchars($row['actual_price']); ?> <span class="text-gray-500 fw-normal">/Qty</span></span>
                                            </div>

                                            <form method="GET" action="product_detail.php">
                                                <input type="hidden" name="product_code" value="<?= $row['product_code']; ?>">
                                                <input type="hidden" name="type" value="<?= urlencode($section); ?>">
                                                <button type="submit" class="btn bg-gray-50 text-heading hover-bg-main-600 hover-text-white w-100 py-10 rounded-8 fw-medium">
                                                    Quick View <i class="ph ph-eye ms-1"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>