<?php 
// invoice.php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../backend/pages/db.php'; // your DB connection file

// Get order id (after placing order, redirect here with ?order_id=..)
$order_id = intval($_GET['order_id'] ?? 0);
if ($order_id <= 0) {
    die("Invalid Order ID");
}

// Fetch order details
$orderSql = "SELECT o.*, c.name, c.email, c.mobile_num, c.address, c.city, c.state, c.zip 
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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #<?= $order['id'] ?></title>
  <style>
    @page { size: A4; margin: 20mm; }
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
    .invoice-box { width: 700px; height:900px; margin: auto; border: 1px solid #000; padding: 20px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #000; padding-bottom: 15px; }
    .company { font-size: 16px; font-weight: bold; }
    .invoice-title { font-size: 32px; font-weight: bold; color: #ac5393; }
    .details, .bill-ship, .items { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .details td, .bill-ship td, .items th, .items td { border: 1px solid #000; padding: 10px; font-size: 14px; }
    .items th { background: #ac5393; color: #fff; text-align: center; }
    .summary { width: 100%; margin-top: 20px; display: flex; justify-content: space-between; }
    .terms { width: 50%; font-size: 14px; }
    .totals { width: 40%; background: #cfe2ff; border: 1px solid #000; }
    .totals td { padding: 10px; border: 1px solid #000; font-size: 14px; }
    .totals td:first-child { font-weight: bold; }
    .qr-box { text-align: center; margin-top: 20px; }
    .qr-box img { width: 120px; height: 120px; }
  </style>
</head>
<body>

<div class="invoice-box">

  <!-- Header -->
  <div class="header">
    <div class="company">
      GRM Elite Wear<br>
      14B, Northern Street<br>
      Greater South Avenue<br>
      New York, NY 10001<br>
      U.S.A
    </div>
    <div class="invoice-title">INVOICE</div>
     <div class="qr-box">
    <p><b>Scan to Track Order</b></p>
    <img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=<?= urlencode('https://yourwebsite.com/track.php?order_id='.$order['id']) ?>" alt="QR Code">
  </div>
  </div>

  <!-- Invoice Details -->
  <table class="details">
    <tr>
      <td><b>Invoice#</b><br>INV-<?= str_pad($order['id'], 6, "0", STR_PAD_LEFT) ?></td>
      <td><b>Invoice Date</b><br><?= date("d M Y", strtotime($order['created_at'])) ?></td>
      <td><b>Terms</b><br>Due on Receipt</td>
      <td><b>Due Date</b><br><?= date("d M Y", strtotime($order['created_at'])) ?></td>
    </tr>
  </table>

  <!-- Bill To / Ship To -->
  <table class="bill-ship">
    <tr>
      <td><b>Bill To</b><br>
        <b><?= htmlspecialchars($order['name']) ?></b><br>
        <?= htmlspecialchars($order['address']) ?><br>
        <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['zip']) ?><br>
        <?= htmlspecialchars($order['mobile_num']) ?><br>
        <?= htmlspecialchars($order['email']) ?>
      </td>
      <td><b>Ship To</b><br>
        <?= htmlspecialchars($order['address']) ?><br>
        <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['zip']) ?>
      </td>
    </tr>
  </table>

  <!-- Items -->
  <table class="items">
    <tr>
      <th>#</th>
      <th>Item & Description</th>
      <th>Size</th>
      <th>Qty</th>
      <th>Rate</th>
      <th>Amount</th>
    </tr>
    <?php 
    $grandTotal = 0; $i=1;
    foreach ($items as $it): 
      $total = $it['quantity'] * $it['unit_price'];
      $grandTotal += $total;
    ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($it['product_name']) ?></td>
      <td><?= htmlspecialchars($it['size']) ?></td>
      <td><?= $it['quantity'] ?></td>
      <td>₹<?= number_format($it['unit_price'],2) ?></td>
      <td>₹<?= number_format($total,2) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <!-- Summary -->
  <div class="summary">
    <div class="terms">
      <p>Thanks for shopping with us.</p>
      <p><b>Terms & Conditions</b><br>
      Full payment is due upon receipt of this invoice.<br>
      Late payments may incur additional charges.</p>
    </div>
    <table class="totals">
      <tr>
        <td>Sub Total</td>
        <td>₹<?= number_format($grandTotal,2) ?></td>
      </tr>
      <tr>
        <td>Tax Rate</td>
        <td>18%</td>
      </tr>
      <tr>
        <td>Total</td>
        <td><b>₹<?= number_format($grandTotal * 1.05,2) ?></b></td>
      </tr>
      <tr>
        <td>Balance Due</td>
        <td><b>₹<?= number_format($grandTotal * 1.05,2) ?></b></td>
      </tr>
    </table>
  </div>


</div>

</body>
</html>
