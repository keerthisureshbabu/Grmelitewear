<?php
session_start();

git filter-repo --path grmfront/checkout.php --invert-paths

// Generate and send OTP
function send_otp(string $phone_number): bool {
    $otp = strval(random_int(100000, 999999));
    $_SESSION['checkout_otp']      = $otp;
    $_SESSION['checkout_otp_time'] = time();
    $to_e164 = '+91' . $phone_number;
    return send_whatsapp_otp($to_e164, $otp);
}

// Verify OTP
function verify_otp(string $user_otp): bool {
    if (!isset($_SESSION['checkout_otp'], $_SESSION['checkout_otp_time'])) return false;
    if (time() - $_SESSION['checkout_otp_time'] > 180) return false; // expired
    return hash_equals($_SESSION['checkout_otp'], $user_otp);
}
?>

