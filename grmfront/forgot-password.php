<?php
session_start();
include('../backend/pages/db.php');

$error_message = '';
$success_message = '';

// Handle password reset request
if (isset($_POST['reset_request'])) {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error_message = "Please enter your email address";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, name FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // In a real application, you would:
            // 1. Generate a unique reset token
            // 2. Store it in the database with expiration
            // 3. Send an email with reset link
            
            // For now, just show a success message
            $success_message = "If an account with that email exists, we have sent a password reset link. Please check your email.";
        } else {
            // Don't reveal if email exists or not for security
            $success_message = "If an account with that email exists, we have sent a password reset link. Please check your email.";
        }
        $stmt->close();
    }
}

include("header.php");
?>

<div class="breadcrumb mb-0 py-26 bg-main-two-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Forgot Password</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="index.php" class="text-gray-900 flex-align gap-8 hover-text-main-600">
                        <i class="ph ph-house"></i> Home
                    </a>
                </li>
                <li class="flex-align"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm text-main-600">Forgot Password</li>
            </ul>
        </div>
    </div>
</div>

<section class="account py-80">
    <div class="container container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="border border-gray-100 hover-border-main-600 transition-1 rounded-16 px-24 py-40">
                    <h6 class="text-xl mb-32 text-center">Forgot Your Password?</h6>
                    <p class="text-muted text-center mb-32">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                    
                    <form method="POST" action="">
                        <div class="mb-24">
                            <label for="email" class="text-neutral-900 text-lg mb-8 fw-medium">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="common-input" id="email" name="email" 
                                   placeholder="Enter your email address" required>
                        </div>
                        
                        <button type="submit" name="reset_request" class="btn btn-main py-18 px-40 w-100">
                            Send Reset Link
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="account.php" class="text-decoration-none">
                                <i class="ph ph-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Messages -->
                <?php if ($error_message): ?>
                    <div class="alert alert-danger mt-4 text-center">
                        <i class="ph ph-warning me-2"></i><?= htmlspecialchars($error_message) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-success mt-4 text-center">
                        <i class="ph ph-check-circle me-2"></i><?= htmlspecialchars($success_message) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>
