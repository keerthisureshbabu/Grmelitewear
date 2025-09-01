<?php
// invoice.php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/db.php';

// Get order id (after placing order, redirect here with ?order_id=..)
$order_id = intval($_GET['order_id'] ?? 0);
if ($order_id <= 0) {
    die("Invalid Order ID");
}

// Fetch order details
$orderSql = "SELECT o.*, c.name, c.email, c.mobile_num 
             FROM orders o
             JOIN customers c ON o.customer_id = c.id
             WHERE o.id = ?";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found");
}

// Fetch order items
$itemSql = "SELECT product_name, size, quantity, unit_price 
            FROM order_items WHERE order_id = ?";
$stmt2 = $conn->prepare($itemSql);
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Invoice #<?= $order['id'] ?></title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .invoice-box { border: 1px solid #eee; padding: 20px; max-width: 700px; margin: auto; }
    table { width: 100%; border-collapse: collapse; }
    table, th, td { border: 1px solid #ddd; padding: 8px; }
    th { background: #f2f2f2; }
    h2 { margin-top: 0; }
  </style>
</head>
<body>
<div class="invoice-box">
  <h2>Invoice #<?= $order['id'] ?></h2>
  <p><strong>Date:</strong> <?= date("d-m-Y", strtotime($order['created_at'])) ?></p>
  <p><strong>Customer:</strong> <?= htmlspecialchars($order['name']) ?><br>
     <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
     <strong>Mobile:</strong> <?= htmlspecialchars($order['mobile_num']) ?></p>

  <table>
    <tr>
      <th>Product</th>
      <th>Size</th>
      <th>Qty</th>
      <th>Unit Price</th>
      <th>Total</th>
    </tr>
    <?php 
    $grandTotal = 0;
    foreach ($items as $it): 
      $total = $it['quantity'] * $it['unit_price'];
      $grandTotal += $total;
    ?>
    <tr>
      <td><?= htmlspecialchars($it['product_name']) ?></td>
      <td><?= htmlspecialchars($it['size']) ?></td>
      <td><?= $it['quantity'] ?></td>
      <td>₹<?= number_format($it['unit_price'],2) ?></td>
      <td>₹<?= number_format($total,2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="4" align="right"><strong>Grand Total</strong></td>
      <td><strong>₹<?= number_format($grandTotal,2) ?></strong></td>
    </tr>
  </table>
</div>
</body>
</html>
