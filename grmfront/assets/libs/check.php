<?php

declare(strict_types=1);
// session_start(); // Do NOT start session here
require_once __DIR__ . '/../../../backend/pages/db.php';
require_once __DIR__ . '/../../checkout_otp.php'; // <-- Add this line

// ...existing code...

// ---------- CONFIG ----------
$seller_state     = 'Tamil Nadu';
$default_gst_rate = 18.0;
$upi_id_payee     = 'yourid@upi';              
$upi_qr_image     = 'assets/images/upi_qr.png';
$otp_valid_secs   = 180;                       
// ---------------------------

$error_message = '';
$current_step  = max(1, min(3, (int)($_GET['step'] ?? 1)));

// ================== Helpers ==================
function luhn_check(string $number): bool {
  $number = preg_replace('/\D/', '', $number);
  $sum = 0; $alt = false;
  for ($i = strlen($number)-1; $i >= 0; $i--) {
    $n = (int)$number[$i];
    if ($alt) { $n *= 2; if ($n > 9) $n -= 9; }
    $sum += $n; $alt = !$alt;
  }
  return ($sum % 10) === 0;
}

function detect_card_brand(string $number): string {
  $v = preg_replace('/\D/', '', $number);
  if (preg_match('/^4/', $v)) return 'visa';
  if (preg_match('/^(5[1-5])/', $v) || preg_match('/^(222[1-9]|22[3-9]\d|2[3-6]\d{2}|27[01]\d|2720)/', $v)) return 'mastercard';
  if (preg_match('/^(60|6521|6522|508|65)/', $v)) return 'rupay';
  return 'unknown';
}

function compute_shipping_for_state(?string $state, float $cart_total): float {
  $state_norm = strtolower(trim($state ?? ''));
  if ($cart_total > 1000) return 0.0;
  if ($state_norm === 'tamil nadu' || $state_norm === 'tamilnadu') return 70.0;
  return 120.0;
}

/** Meta WhatsApp OTP */
function send_whatsapp_otp(string $to_e164, string $otp): bool {
    $token = getenv('META_WABA_TOKEN');        // Your Meta token
    $phone_number_id = getenv('META_PHONE_ID'); // Your Meta WABA phone number ID
    if (!$token || !$phone_number_id) {
        error_log("Meta WhatsApp API: Missing token or phone_number_id");
        return false;
    }
    $url = "https://graph.facebook.com/v19.0/{$phone_number_id}/messages";

    $to_number = preg_replace('/^\+/', '', $to_e164); // Remove + for Meta API

    $data = [
        'messaging_product' => 'whatsapp',
        'to'                => $to_number,
        'type'              => 'text',
        'text'              => ['body' => "Your OTP for GRM Elite Wear checkout is: $otp"]
    ];

    $ch = curl_init($url);
    if (!$ch) {
        error_log("Meta WhatsApp API: Failed to initialize cURL");
        return false;
    }
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_TIMEOUT        => 30, // Add timeout
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        error_log("Meta WhatsApp API cURL error: $error");
        curl_close($ch);
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    } else {
        error_log("Meta WhatsApp API failed: HTTP $httpCode, Response: $response");
        return false;
    }
}


// Cart totals
$cart_subtotal  = 0.0; $total_quantity = 0;
foreach ($_SESSION['cart'] as $it) {
  $p = (float)($it['price'] ?? 0.0);
  $q = max(1, (int)($it['qty'] ?? 1));
  $cart_subtotal += $p * $q; $total_quantity += $q;
}

// Pull session data
$contact = $_SESSION['contact'] ?? [];                    
$shipping_address = $_SESSION['shipping_address'] ?? [];
$payment_method   = $_SESSION['payment_method'] ?? [];

// ================== POST handlers ==================
// Step 1: Request OTP
if ($current_step === 1 && ($_POST['action'] ?? '') === 'send_otp') {
  $wa = trim($_POST['whatsapp'] ?? '');
  $digits = preg_replace('/\D/','',$wa);
  $wa_e164 = (strlen($digits) === 10) ? '+91'.$digits : '+'.$digits;

  if (strlen($digits) < 10) {
    $error_message = 'Enter a valid WhatsApp number.';
  } else {
    if (!send_otp($digits)) { // Use helper from checkout_otp.php
      $error_message = 'Could not send OTP. Try again.';
    } else {
      $_SESSION['contact'] = [
        'wa' => $wa_e164,
        'otp_ok' => 0,
      ];
    }
  }
}

// Step 1: Verify OTP
if ($current_step === 1 && ($_POST['action'] ?? '') === 'verify_otp') {
  $code = trim($_POST['otp'] ?? '');
  if (!verify_otp($code)) {
    $error_message = 'Incorrect or expired OTP.';
  } else {
    $_SESSION['contact']['otp_ok'] = 1;
    header('Location: checkout.php?step=2'); exit;
  }
}
// Step 2: Shipping address
// Step 2: Shipping address
if ($current_step === 2 && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $error_message = 'Invalid request.';
  } else {
    // ...existing shipping address logic...
  }
}

// Step 3: Payment (UPI / Card)
if ($current_step === 3 && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $error_message = 'Invalid request.';
  } else {
    // ...existing payment logic...
  }
}


// ======== Tax / Totals =========
$shipping_price = compute_shipping_for_state($shipping_address['state'] ?? '', $cart_subtotal);
$taxable_value  = round($cart_subtotal + $shipping_price, 2);
$gst_rate       = (float)$default_gst_rate;
$buyer_state    = strtolower(trim($shipping_address['state'] ?? ''));
$seller_state_l = strtolower(trim($seller_state));
$same_state     = ($buyer_state !== '' && $buyer_state === $seller_state_l);
$gst_amount     = round(($taxable_value * $gst_rate)/100, 2);
if ($same_state) { $cgst = round($gst_amount/2,2); $sgst = round($gst_amount/2,2); $igst = 0.0; }
else { $cgst = 0.0; $sgst = 0.0; $igst = $gst_amount; }
$grand_total = round($cart_subtotal + $shipping_price + $gst_amount, 2);

// UI helpers
function is_otp_sent(): bool { return !empty($_SESSION['contact']['otp']) && empty($_SESSION['contact']['otp_ok']); }
function is_otp_verified(): bool { return !empty($_SESSION['contact']['otp_ok']); }


?>

<!-- Your existing HTML / CSS / JS checkout UI remains unchanged -->


