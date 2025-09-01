<?php
include ('orderanalysis.php');

// ---------- CATEGORY-WISE TODAY DATA ----------
$catData = [
  'kids' => 0,
  'womens' => 0,
  'toys' => 0,
  'accessories' => 0
];

$sqlCat = "
    SELECT 
        CASE 
            WHEN UPPER(oi.variation_id) LIKE 'KIDS%' THEN 'kids'
            WHEN UPPER(oi.variation_id) LIKE 'WOMEN%' THEN 'womens'
            WHEN UPPER(oi.variation_id) LIKE 'TOYS%' THEN 'toys'
            WHEN UPPER(oi.variation_id) LIKE 'ACCESSORIES%' THEN 'accessories'
        END AS category,
        COUNT(*) as total
    FROM orders o
    INNER JOIN order_items oi ON o.id = oi.order_id
    WHERE YEAR(o.created_at)=YEAR(CURDATE()-INTERVAL 1 MONTH) AND MONTH(o.created_at)=MONTH(CURDATE()-INTERVAL 1 MONTH)
    GROUP BY category
";
$resCat = $conn->query($sqlCat);
while ($row = $resCat->fetch_assoc()) {
    if (!empty($row['category'])) {
        $catData[$row['category']] = (int)$row['total'];
    }
}

// ---------- TODAY'S ORDER ITEMS ----------
$orderItems = [];
$sqlItems = "
    SELECT 
        o.id,
        o.order_status,
        oi.variation_id,
        oi.product_name,
        oi.size,
        oi.unit_price,
        oi.quantity
    FROM orders o
    INNER JOIN order_items oi ON o.id = oi.order_id
    WHERE YEAR(o.created_at)=YEAR(CURDATE()-INTERVAL 1 MONTH) AND MONTH(o.created_at)=MONTH(CURDATE()-INTERVAL 1 MONTH)
";
$resItems = $conn->query($sqlItems);
while($row = $resItems->fetch_assoc()){
    $orderItems[] = $row;
}
?>


    <!-- Line Chart -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card custom-card bg-primary-transparent border border-primary border-opacity-10">
          <div class="card-header border-0">
            <h5 class="card-title mb-0" style="color: #bcbfc4;">📊 Previous Month's Orders by Category</h5>
          </div>
          <div class="card-body">
            <canvas id="prevmonthOrdersChart" height="120"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Table of Today's Orders -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card custom-card">
          <div class="card-header border-0" style="background:#4e4eff; color:white;">
            <h5 class="card-title mb-0">📋 Previous Month's Order Details</h5>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered m-0">
              <thead style="background:#282c34; color:#fff;">
                <tr>
                  <th>Order ID</th>
                  <th>Variation ID</th>
                  <th>Product Name</th>
                  <th>Size</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($orderItems) > 0): ?>
                  <?php foreach($orderItems as $item): ?>
                  <tr style="background:#f1f3f4;">
                    <td><?= htmlspecialchars($item['id']); ?></td>
                    <td><?= htmlspecialchars($item['variation_id']); ?></td>
                    <td><?= htmlspecialchars($item['product_name']); ?></td>
                    <td><?= htmlspecialchars($item['size']); ?></td>
                    <td><?= htmlspecialchars($item['unit_price']); ?></td>
                    <td><?= htmlspecialchars($item['quantity']); ?></td>
                    <td><?=htmlspecialchars($item['order_status']); ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" class="text-center">No orders found for today.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('prevmonthOrdersChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(139,126,255,0.7)');
gradient.addColorStop(1, 'rgba(139,126,255,0.1)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Kids', 'Womens', 'Toys', 'Accessories'],
    datasets: [{
      label: 'Today Orders',
      data: [
        <?= $catData['kids'] ?>,
        <?= $catData['womens'] ?>,
        <?= $catData['toys'] ?>,
        <?= $catData['accessories'] ?>
      ],
      borderColor: '#8b7eff',
      backgroundColor: gradient,
      fill: true,
      borderWidth: 4,
      tension: 0.4,
      pointRadius: 6,
      pointHoverRadius: 8,
      pointBackgroundColor: '#ffeb3b',
      pointBorderColor: '#000',
      pointBorderWidth: 2,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        labels: { color: '#fff', font: { size: 14, weight: 'bold' } }
      },
      tooltip: {
        backgroundColor: '#222',
        titleColor: '#fff',
        bodyColor: '#ddd'
      }
    },
    scales: {
      x: {
        ticks: { color: '#fff', font: { size: 14 } },
        grid: { color: 'rgba(255,255,255,0.1)' }
      },
      y: {
        beginAtZero: true,
        ticks: { color: '#fff', font: { size: 14 } },
        grid: { color: 'rgba(255,255,255,0.1)' }
      }
    }
  }
});
</script>

<?php include('footer.php'); ?>
