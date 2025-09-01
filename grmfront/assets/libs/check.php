<?php 
// checkout.php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../../backend/pages/db.php'; 

// ---------- CONFIG ----------
$seller_state     = 'Tamil Nadu';
$default_gst_rate = 18.0;
$upi_id_payee     = 'yourid@upi';              
$upi_qr_image     = 'assets/images/upi_qr.png';
$otp_valid_secs   = 180;                       
// ---------------------------

// Gate: require login
if (empty($_SESSION['user_id'])) {
  $_SESSION['redirect_after_login'] = 'checkout.php';
  header('Location: account.php'); exit;
}

// Gate: require cart
if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
  header('Location: cart.php'); exit;
}

$user_id   = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

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
    $token = getenv('META_WABA_TOKEN');      
    $phone_number_id = getenv('META_PHONE_ID'); 
    $url = "https://graph.facebook.com/v17.0/{$phone_number_id}/messages";

    // Remove '+' for Meta API
    $to_number = preg_replace('/^\+/', '', $to_e164);

    $data = [
        'messaging_product' => 'whatsapp',
        'to'                => $to_number,
        'type'              => 'text',
        'text'              => [
            'body' => "Your GRM Elite Wear OTP is: {$otp}. It is valid for 3 minutes."
        ]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS     => json_encode($data),
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpCode >= 200 && $httpCode < 300);
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
    $otp = (string)random_int(100000,999999);
    $_SESSION['contact'] = [
      'wa' => $wa_e164,
      'otp' => $otp,
      'otp_exp' => time() + $otp_valid_secs,
      'otp_ok' => 0,
    ];
    if (!send_whatsapp_otp($wa_e164, $otp)) {
      $error_message = 'Could not send OTP. Try again.';
    }
  }
}

// Step 1: Verify OTP
if ($current_step === 1 && ($_POST['action'] ?? '') === 'verify_otp') {
  $code = trim($_POST['otp'] ?? '');
  if (empty($_SESSION['contact']['otp'])) {
    $error_message = 'Please request a new OTP.';
  } elseif (time() > (int)$_SESSION['contact']['otp_exp']) {
    $error_message = 'OTP expired. Request a new one.';
  } elseif ($code !== $_SESSION['contact']['otp']) {
    $error_message = 'Incorrect OTP.';
  } else {
    $_SESSION['contact']['otp_ok'] = 1;
    header('Location: checkout.php?step=2'); exit;
  }
}

// Step 2: Shipping address
if ($current_step === 2 && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  $shipping_data = [
    'first_name' => trim($_POST['first_name'] ?? ''),
    'last_name'  => trim($_POST['last_name']  ?? ''),
    'address1'   => trim($_POST['address1']   ?? ''),
    'address2'   => trim($_POST['address2']   ?? ''),
    'zip_code'   => trim($_POST['zip_code']   ?? ''),
    'city'       => trim($_POST['city']       ?? ''),
    'state'      => trim($_POST['state']      ?? ''),
    'mobile'     => trim($_POST['mobile']     ?? ''),
  ];
  $required = ['first_name','last_name','address1','zip_code','city','state','mobile'];
  $missing = [];
  foreach ($required as $f) if ($shipping_data[$f]==='') $missing[] = ucfirst(str_replace('_',' ',$f));
  if ($missing) { $error_message = 'Fill required fields: '.implode(', ',$missing); }
  else {
    $_SESSION['shipping_address'] = $shipping_data;
    header('Location: checkout.php?step=3'); exit;
  }
}

// Step 3: Payment (UPI / Card)
if ($current_step === 3 && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  $method = trim($_POST['payment_method'] ?? '');
  if ($method === 'upi') {
    $upi_entered = trim($_POST['upi_id'] ?? '');
    if ($upi_entered === '') $error_message = 'Enter UPI ID.';
    else {
      $_SESSION['payment_method'] = ['method'=>'upi','upi_id'=>$upi_entered];
      header('Location: order_place.php'); exit;
    }
  } elseif (in_array($method, ['debit_card','credit_card'], true)) {
    $card_number = trim($_POST['card_number'] ?? '');
    $expiry      = trim($_POST['expiry']      ?? '');
    $cvv         = trim($_POST['cvv']         ?? '');
    $card_holder = trim($_POST['card_holder'] ?? '');
    $clean       = preg_replace('/\D/','',$card_number);
    if ($card_holder==='' || $expiry==='' || $cvv==='' || $clean==='') {
      $error_message = 'Enter all card fields.';
    } elseif (!luhn_check($clean)) {
      $error_message = 'Invalid card number.';
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/',$expiry)) {
      $error_message = 'Invalid expiry format.';
    } else {
      [$mm,$yy] = explode('/',$expiry);
      $expY = 2000 + (int)$yy; $expM = (int)$mm; $nowY=(int)date('Y'); $nowM=(int)date('n');
      if ($expY < $nowY || ($expY === $nowY && $expM < $nowM)) $error_message='Card expired.';
      elseif (!preg_match('/^\d{3,4}$/',$cvv)) $error_message='Invalid CVV.';
      else {
        $brand = detect_card_brand($clean);
        if (!in_array($brand, ['visa','mastercard','rupay'], true)) $error_message='We accept Visa/Mastercard/RuPay only.';
        else {
          $_SESSION['payment_method'] = [
            'method'=>$method,
            'card_brand'=>$brand,
            'card_last4'=>substr($clean,-4),
            'card_holder'=>$card_holder
          ];
          header('Location: order_place.php'); exit;
        }
      }
    }
  } else {
    $error_message = 'Select a valid payment method.';
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


