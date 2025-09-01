<?php
include 'db.php';
include 'header.php';

$sql = "
    SELECT id, variation_code, product_name, image1, attribute, inventory_count, mrp_price, actual_price
    FROM kids_products
    UNION ALL
    SELECT id, variation_code, product_name, image1, attribute, inventory_count, mrp_price, actual_price
    FROM womens_products
    UNION ALL
    SELECT id, variation_code, product_name, image1, attribute, inventory_count, mrp_price, actual_price
    FROM toys_products
    UNION ALL
    SELECT id, variation_code, product_name, image1, attribute, inventory_count, mrp_price, actual_price
    FROM accessories_products
";

$result = $conn->query($sql);
?>

<div class="main-content app-content">
  <div class="container-fluid">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
      <h1 class="page-title fw-semibold fs-18 mb-0">All Products</h1>
      <div class="ms-md-1 ms-0">
        <nav>
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Products</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12">
        <div class="card custom-card">
          <div class="card-header border-0" style="background:#4e4eff; color:white;">
            <h5 class="card-title mb-0">📋 All Products (Kids, Womens, Toys, Accessories)</h5>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered m-0 align-middle">
              <thead style="background:#282c34; color:#fff;">
                <tr>
                  <th>ID</th>
                  <th>Variation ID</th>
                  <th>Product Name</th>
                  <th>Image</th>
                  <th>Attribute</th>
                  <th>Inventory Count</th>
                  <th>MRP Price</th>
                  <th>Actual Price</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                  <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="background:#f1f3f4;">
                      <td><?= htmlspecialchars($row['id']); ?></td>
                      <td><?= htmlspecialchars($row['variation_code']); ?></td>
                      <td><?= htmlspecialchars($row['product_name']); ?></td>
                      <td>
                        <?php if(!empty($row['image1'])): ?>
                          <img src="<?= htmlspecialchars($row['image1']); ?>" alt="Image" style="width:60px;height:auto;">
                        <?php else: ?>
                          <span>No Image</span>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($row['attribute']); ?></td>
                      <td><?= htmlspecialchars($row['inventory_count']); ?></td>
                      <td><?= htmlspecialchars($row['mrp_price']); ?></td>
                      <td><?= htmlspecialchars($row['actual_price']); ?></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="8" class="text-center">No products found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer border-top-0">
                <div class="d-flex justify-content-between">
                    <div>Showing <b>1</b> to <b><?= $result->num_rows ?></b> entries</div>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </div>
            </div>

  </div>
</div>

<?php include('footer.php'); ?>
