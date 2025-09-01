<?php
// checkout.php
declare(strict_types=1);

require_once __DIR__ . '/assets/libs/check.php'; // Only include logic once

include __DIR__ . '/header.php';
?>
<!-- ===== BREADCRUMB ===== -->
<div class="breadcrumb mb-0 py-26 bg-main-two-50">
  <div class="container container-lg">
    <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
      <h6 class="mb-0">Checkout</h6>
      <ul class="flex-align gap-8 flex-wrap">
        <li class="text-sm"><a href="index.php" class="text-gray-900 flex-align gap-8 hover-text-main-600"><i class="ph ph-house"></i> Home</a></li>
        <li class="flex-align"><i class="ph ph-caret-right"></i></li>
        <li class="text-sm text-main-600">Checkout</li>
      </ul>
    </div>
  </div>
</div>

<section class="container py-80">
<div class="container container-lg">
<div class="row">
  <!-- LEFT -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-main text-white-center">
        <h4 class="mb-0">Checkout</h4>
        <p class="mb-0 text-white-50">Order subtotal (<?= (int)$total_quantity ?> items): ₹<?= number_format($cart_subtotal,2) ?></p>
      </div>
      <div class="card-body p-4">

        <div class="checkout-progress mb-4">
          <div class="row text-center">
            <div class="col-4">
              <div class="step <?= $current_step>=1 ? 'active':'' ?>">
                <div class="step-number">1</div><div class="step-title">Contact (WhatsApp OTP)</div>
              </div>
            </div>
            <div class="col-4">
              <div class="step <?= $current_step>=2 ? 'active':'' ?>">
                <div class="step-number">2</div><div class="step-title">Shipping Address</div>
              </div>
            </div>
            <div class="col-4">
              <div class="step <?= $current_step>=3 ? 'active':'' ?>">
                <div class="step-number">3</div><div class="step-title">Payment Method</div>
              </div>
            </div>
          </div>
        </div>

        <?php if ($error_message): ?>
          <div class="alert alert-danger"><i class="ph ph-warning me-2"></i><?= htmlspecialchars($error_message,ENT_QUOTES,'UTF-8') ?></div>
        <?php endif; ?>

        <!-- ===== Step 1: Contact / OTP ===== -->
        <?php if ($current_step === 1): ?>
          <div class="step-content">
            <?php if (!is_otp_verified()): ?> <br>
              <h5 class="mb-3">Verify Your Number</h5> <br>
              <form method="POST" action="checkout.php?step=1" class="mb-3">
                <div class="row g-3">
                  <div class="col-md-8">
                    <label class="form-label">WhatsApp Number</label>
                    <input type="text" class="form-control" name="whatsapp" placeholder="9876543210 or +919876543210" value="<?= htmlspecialchars($_SESSION['contact']['wa'] ?? '',ENT_QUOTES) ?>">
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-main w-100" name="action" value="send_otp" type="submit">Send OTP</button>
                  </div>
                </div>
              </form>

              <?php if (is_otp_sent()): ?>
                <form method="POST" action="checkout.php?step=1" id="otpForm">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Enter OTP</label>
                      <input type="text" class="form-control" name="otp" maxlength="6" pattern="\d{6}" required>
                      <div class="form-text">OTP sent to <?= htmlspecialchars($_SESSION['contact']['wa'],ENT_QUOTES) ?>. <span id="otpTimer"></span></div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                      <button class="btn btn-main w-100" name="action" value="verify_otp" type="submit">Verify</button>
                    </div>
                  </div>
                </form>
              <?php endif; ?>
            <?php else: ?>
              <div class="alert alert-success">WhatsApp verified: <?= htmlspecialchars($_SESSION['contact']['wa'],ENT_QUOTES) ?>. <a href="checkout.php?step=2" class="ms-2">Continue →</a></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- ===== Step 2: Shipping Address ===== -->
        <?php if ($current_step === 2): ?>
          <?php if (!is_otp_verified()) { header('Location: checkout.php?step=1'); exit; } ?>
          <div class="step-content">
            <h5 class="mb-3">Shipping Address</h5>
            <form method="POST" action="checkout.php?step=2" novalidate>
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label">First Name *</label>
                  <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($shipping_address['first_name'] ?? '',ENT_QUOTES) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Last Name *</label>
                  <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($shipping_address['last_name'] ?? '',ENT_QUOTES) ?>" required></div>
                <div class="col-12"><label class="form-label">Address *</label>
                  <input type="text" class="form-control" name="address1" value="<?= htmlspecialchars($shipping_address['address1'] ?? '',ENT_QUOTES) ?>" required></div>
                <div class="col-12"><label class="form-label">Address Line 2</label>
                  <input type="text" class="form-control" name="address2" value="<?= htmlspecialchars($shipping_address['address2'] ?? '',ENT_QUOTES) ?>"></div>
                <div class="col-md-6"><label class="form-label">ZIP *</label>
                  <input type="text" class="form-control" name="zip_code" value="<?= htmlspecialchars($shipping_address['zip_code'] ?? '',ENT_QUOTES) ?>" required></div>
                <div class="col-md-6"><label class="form-label">City *</label>
                  <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($shipping_address['city'] ?? '',ENT_QUOTES) ?>" required></div>
                <div class="col-md-6"><label class="form-label">State *</label>
                  <select class="form-select" name="state" required>
                    <option value="">Select...</option>
                    <?php
                      $states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal"];
                      $selState = $shipping_address['state'] ?? '';
                      foreach ($states as $s) {
                        $sel = ($selState === $s) ? 'selected' : '';
                        echo '<option value="'.htmlspecialchars($s,ENT_QUOTES).'">'.$s.'</option>';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-6"><label class="form-label">Mobile *</label>
                  <input type="tel" class="form-control" name="mobile" value="<?= htmlspecialchars($shipping_address['mobile'] ?? '',ENT_QUOTES) ?>" required></div>
              </div>
              <div class="mt-4"><button class="btn btn-main px-4 py-20" type="submit">Continue to Payment</button></div>
            </form>
          </div>
        <?php endif; ?>

        <!-- ===== Step 3: Payment ===== -->
        <?php if ($current_step === 3): ?>
          <?php if (empty($shipping_address)) { header('Location: checkout.php?step=2'); exit; } ?>
          <div class="step-content">
            <h5 class="mb-3">Payment Method</h5>
            <form method="POST" action="checkout.php?step=3" id="paymentForm" autocomplete="off" novalidate>
              <div class="payment-options mb-4">
                <div class="form-check mb-3 p-3 border rounded">
                  <input class="form-check-input" type="radio" name="payment_method" id="upi" value="upi" required>
                  <label class="form-check-label w-100" for="upi">
                    <div class="d-flex align-items-center"><i class="ph ph-credit-card me-2 text-primary"></i><strong>UPI (QR or UPI ID)</strong></div>
                  </label>
                </div>
                <div class="form-check mb-3 p-3 border rounded">
                  <input class="form-check-input" type="radio" name="payment_method" id="debit_card" value="debit_card" required>
                  <label class="form-check-label w-100" for="debit_card">
                    <div class="d-flex align-items-center justify-content-between">
                      <div><i class="ph ph-credit-card me-2 text-primary"></i><strong>Debit Card</strong></div>
                      <div class="payment">
                        <img src="assets/images/payment/mastercard.png" style="width:30px;">
                        <img src="assets/images/payment/visa.png" style="width:30px;">
                        <img src="assets/images/payment/rupay.png" style="width:30px;">
                      </div>
                    </div>
                  </label>
                </div>
                <div class="form-check mb-3 p-3 border rounded">
                  <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" required>
                  <label class="form-check-label w-100" for="credit_card">
                    <div class="d-flex align-items-center justify-content-between">
                      <div><i class="ph ph-credit-card me-2 text-primary"></i><strong>Credit Card</strong></div>
                      <div class="payment">
                        <img src="assets/images/payment/mastercard.png" style="width:30px;">
                        <img src="assets/images/payment/visa.png" style="width:30px;">
                        <img src="assets/images/payment/rupay.png" style="width:30px;">
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- UPI -->
              <div id="upiFields" class="payment-fields" style="display:none;">
                <div class="row g-3">
                  <div class="col-md-6 text-center">
                    <?php if (is_file($upi_qr_image)): ?>
                      <img src="<?= htmlspecialchars($upi_qr_image,ENT_QUOTES) ?>" alt="UPI QR" style="max-width:220px;">
                    <?php else: ?>
                      <div class="text-muted">Add QR at <code><?= htmlspecialchars($upi_qr_image,ENT_QUOTES) ?></code></div>
                    <?php endif; ?>
                    <div class="small mt-2">UPI Payee: <strong><?= htmlspecialchars($upi_id_payee,ENT_QUOTES) ?></strong></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Or enter your UPI ID *</label>
                    <input type="text" class="form-control" name="upi_id" placeholder="example@upi">
                    <div class="form-text">After paying via QR, you can still enter your UPI handle for reference.</div>
                  </div>
                </div>
              </div>

              <!-- Card -->
              <div id="cardFields" class="payment-fields" style="display:none;">
                <input type="hidden" name="card_brand" id="card_brand" value="">
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label">Card Number *</label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="card_number" id="card_number" placeholder="1234 5678 9012 3456" maxlength="23" autocomplete="off">
                      <span class="input-group-text" id="cardBrandIcon" style="min-width:60px;"><small class="text-muted">Card</small></span>
                    </div>
                  </div>
                  <div class="col-md-6"><label class="form-label">Expiry *</label>
                    <input type="text" class="form-control" name="expiry" id="expiry" placeholder="MM/YY" maxlength="5" autocomplete="off"></div>
                  <div class="col-md-6"><label class="form-label">CVV *</label>
                    <input type="text" class="form-control" name="cvv" id="cvv" placeholder="123" maxlength="4" autocomplete="off"></div>
                  <div class="col-12"><label class="form-label">Card Holder Name *</label>
                    <input type="text" class="form-control" name="card_holder" id="card_holder" placeholder="Name on card" autocomplete="off"></div>
                </div>
              </div>

              <div class="mt-4"><button type="submit" class="btn btn-main px-4 py-20">Place Order</button></div>
            </form>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- RIGHT: Summary -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm order-summary-card">
      <div class="card-header bg-light"><h5 class="mb-0">Order Summary</h5></div>
      <div class="card-body">
        <h6 class="mb-3 small text-muted">Items (<?= (int)$total_quantity ?>)</h6>
        <div class="summary-items-list small">
          <?php foreach ($_SESSION['cart'] as $item):
            $iName  = htmlspecialchars($item['product_name'] ?? 'Product', ENT_QUOTES,'UTF-8');
            $iQty   = (int)($item['qty'] ?? 1);
            $iPrice = number_format((float)($item['price'] ?? 0.0) * max(1,$iQty), 2);
            $iImage = htmlspecialchars($item['image'] ?? 'assets/placeholder.png', ENT_QUOTES,'UTF-8');
          ?>
          <div class="summary-item mb-5">
            <img src="../backend/pages/<?= $iImage ?>" alt="<?= $iName ?>" class="rounded me-3" style="width:50px;height:50px;object-fit:cover;">
            <div class="flex-grow-1">
              <div class="fw-semibold small product-name" style="width:150px;"><?= $iName ?></div>
              <div class="text-muted xsmall">Qty: <?= (int)$iQty ?></div>
            </div>
            <div class="text-end"><div class="fw-semibold">₹<?= $iPrice ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
        <hr>
        <div class="cost-breakdown small">
          <div class="d-flex justify-content-between fw-bold"><span>Subtotal</span><span>₹<?= number_format($cart_subtotal,2) ?></span></div>
          <div class="d-flex justify-content-between mt-5"><span>Shipping</span><span>₹<?= number_format($shipping_price,2) ?></span></div>
          <div class="d-flex justify-content-between mt-5"><span>Taxable Value</span><span>₹<?= number_format($taxable_value,2) ?></span></div>
          <div class="mt-5"><strong>GST (<?= number_format($gst_rate,2) ?>%)</strong></div>
          <?php if ($same_state): ?>
            <div class="d-flex justify-content-between text-muted"><span>CGST (<?= number_format($gst_rate/2,2) ?>%)</span><span>₹<?= number_format($cgst,2) ?></span></div>
            <div class="d-flex justify-content-between text-muted"><span>SGST (<?= number_format($gst_rate/2,2) ?>%)</span><span>₹<?= number_format($sgst,2) ?></span></div>
          <?php else: ?>
            <div class="d-flex justify-content-between text-muted"><span>IGST (<?= number_format($gst_rate,2) ?>%)</span><span>₹<?= number_format($igst,2) ?></span></div>
          <?php endif; ?>
          <hr>
          <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>₹<?= number_format($grand_total,2) ?></span></div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</section>

<style>
.btn-main{background:linear-gradient(90deg,#ac5393,#7a3b78);border:none;color:#fff;font-weight:600;transition:all .18s;border-radius:8px;padding:.6rem 1rem}
.btn-main:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.12);color:#fff}
.checkout-progress{position:relative;margin-bottom:1.5rem}
.checkout-progress::after{content:'';position:absolute;top:25px;left:0;right:0;height:2px;background:#e9ecef;z-index:1}
.step{position:relative;z-index:2}
.step-number{width:50px;height:50px;border-radius:50%;background:#e9ecef;color:#6c757d;display:flex;align-items:center;justify-content:center;font-weight:bold;margin:0 auto 10px}
.step.active .step-number{background:#ac5393;color:#fff}
.step-title{font-size:.9rem;color:#6c757d}.step.active .step-title{color:#ac5393;font-weight:600}
.payment-fields{background:#fbfbfb;padding:16px;border-radius:8px;border:1px solid #eef0f2}
.order-summary-card .card-body{padding:1rem;max-height:calc(100vh - 120px);overflow:auto}
.summary-items-list{max-height:300px;overflow-y:auto;padding-right:6px;margin-bottom:.5rem}
.summary-item{gap:10px;align-items:center;display:flex}
.product-name{white-space:normal;overflow:hidden;text-overflow:ellipsis}
.small{font-size:.9rem}.xsmall{font-size:.78rem;color:#6c757d}
</style>

<script src="../grmfront/assets/js/checkout.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
