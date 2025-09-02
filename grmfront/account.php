<?php
session_start();
include('../backend/pages/db.php');

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = '';
$success_message = '';

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all user session variables
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_mobile']);
    unset($_SESSION['user_origin']);
    unset($_SESSION['redirect_after_login']);
    
    // Redirect to home page
    header("Location: index.php");
    exit();
}

// Handle redirect parameter for post-login redirect
if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    $_SESSION['redirect_after_login'] = $_GET['redirect'];
}

// Handle Login
if (isset($_POST['login'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "Invalid request";
    } else {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            $error_message = "Please fill in all fields";
        } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, email, password, name, mobile_num FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_mobile'] = $user['mobile_num'];
                
                // Check if there's a redirect destination stored in session
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect_url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']); // Clear the redirect
                    header("Location: $redirect_url");
                    exit();
                } else {
                    // Redirect to customer dashboard
                    header("Location: checkout.php");
                    exit();
                }
            } else {
                $error_message = "Invalid email or password";
            }
        } else {
            $error_message = "Invalid email or password";
        }
        $stmt->close();
    }
}
}

// Handle Registration
if (isset($_POST['register'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "Invalid request";
    } else {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $mob_number = trim($_POST['mob_number']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $origin = trim($_POST['origin']);

        if (empty($name) || empty($email) || empty($mob_number) || empty($password) || empty($confirm_password) || empty($origin)) {
            $error_message = "Please fill in all fields";
        }
        elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match";
        }
        elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long";
    } elseif (!in_array($origin, ['direct', 'facebook', 'google'])) {
        $error_message = "Please select a valid origin";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "Email already registered";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO customers (name, email, mobile_num, password, origin, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $name, $email, $mob_number, $hashed_password, $origin);
            
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                
                // Store user data in session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_mobile'] = $mob_number;
                $_SESSION['user_origin'] = $origin;
                
                $success_message = "Registration successful! Redirecting...";
                
                // Check if there's a redirect destination stored in session
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect_url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']); // Clear the redirect
                    header("refresh:2;url=$redirect_url");
                    exit();
                } else {
                    // Redirect to customer dashboard after 2 seconds
                    header("refresh:2;url=checkout.php");
                    exit();
                }
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
}
?>

<?php include("header.php"); ?>

<div class="breadcrumb mb-0 py-26 bg-main-two-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">My Account</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="index.php" class="text-gray-900 flex-align gap-8 hover-text-main-600">
                        <i class="ph ph-house"></i> Home
                    </a>
                </li>
                <li class="flex-align"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm text-main-600">Account</li>
            </ul>
        </div>
    </div>
</div>

<section class="account py-80">
    <div class="container container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row gy-4">
                    <!-- Login Section -->
                    <div class="col-xl-6 pe-xl-5">
                        <div class="border border-gray-100 hover-border-main-600 transition-1 rounded-16 px-24 py-40 h-100">
                            <h6 class="text-xl mb-32">Login</h6>
                            <form method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="mb-24">
                                    <label for="email" class="text-neutral-900 text-lg mb-8 fw-medium">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="common-input" id="email" name="email" placeholder="Enter your email address" required>
                                </div>
                                <div class="mb-24">
                                    <label for="login_password" class="text-neutral-900 text-lg mb-8 fw-medium">Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="common-input" id="login_password" name="password" 
                                               placeholder="Enter your password" required>
                                        <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100" type="button" id="toggleLoginPassword">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-24 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                </div>
                                <button type="submit" name="login" class="btn btn-main py-18 px-40">
                                    Log in
                                </button>
                                <div class="text-center mt-3">
                                    <a href="forgot-password.php" class="text-danger text-decoration-none">Forgot your password?</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Register Section -->
                    <div class="col-xl-6">
                        <div class="border border-gray-100 hover-border-main-600 transition-1 rounded-16 px-24 py-40">
                            <h6 class="text-xl mb-32">Register</h6>
                            <form method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="mb-24">
                                    <label for="register_name" class="text-neutral-900 text-lg mb-8 fw-medium">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="common-input" id="register_name" name="name" 
                                           placeholder="Write your full name" required>
                                </div>
                                <div class="mb-24">
                                    <label for="register_email" class="text-neutral-900 text-lg mb-8 fw-medium">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="common-input" id="register_email" name="email" 
                                           placeholder="Enter email address" required>
                                </div>
                                <div class="mb-24">
                                    <label for="mob_number" class="text-neutral-900 text-lg mb-8 fw-medium">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="common-input" id="mob_number" name="mob_number" 
                                           placeholder="Enter your mobile number" required>
                                </div>
                                <div class="mb-24">
                                    <label for="register_password" class="text-neutral-900 text-lg mb-8 fw-medium">Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="common-input" id="register_password" name="password" 
                                               placeholder="Create a password" required>
                                        <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100" type="button" id="toggleRegisterPassword">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-24">
                                    <label for="confirm_password" class="text-neutral-900 text-lg mb-8 fw-medium">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="common-input" id="confirm_password" name="confirm_password" 
                                               placeholder="Confirm your password" required>
                                        <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100" type="button" id="toggleConfirmPassword">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-24">
                                    <label for="origin" class="text-neutral-900 text-lg mb-8 fw-medium">How did you find us? <span class="text-danger">*</span></label>
                                    <select class="common-input" id="origin" name="origin" required>
                                        <option value="">Select an option</option>
                                        <option value="direct">Directly to the website</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="google">Google</option>
                                    </select>
                                </div>
                                <div class="mb-24">
                                    <small class="text-muted">
                                        Your personal data will be used to process your order, support your experience throughout this website, 
                                        and for other purposes described in our <a href="#" class="text-decoration-none">privacy policy</a>.
                                    </small>
                                </div>
                                <button type="submit" name="register" class="btn btn-main py-18 px-40">
                                    Register
                                </button>
                            </form>
                        </div>
                    </div>
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

<script>
// Toggle password visibility
document.getElementById('toggleLoginPassword').addEventListener('click', function() {
    const passwordField = document.getElementById('login_password');
    const icon = this.querySelector('i');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.className = 'ph ph-eye-slash';
    } else {
        passwordField.type = 'password';
        icon.className = 'ph ph-eye';
    }
});

document.getElementById('toggleRegisterPassword').addEventListener('click', function() {
    const passwordField = document.getElementById('register_password');
    const icon = this.querySelector('i');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.className = 'ph ph-eye-slash';
    } else {
        passwordField.type = 'password';
        icon.className = 'ph ph-eye';
    }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const passwordField = document.getElementById('confirm_password');
    const icon = this.querySelector('i');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.className = 'ph ph-eye-slash';
    } else {
        passwordField.type = 'password';
        icon.className = 'ph ph-eye';
    }
});
</script>

<?php include("footer.php");?>