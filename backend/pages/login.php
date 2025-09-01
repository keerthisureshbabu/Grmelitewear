<?php
session_start();
require_once "db.php";  

$error_message = "";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']);

    // Get user id and password from database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_from_db);
        $stmt->fetch();

        // If you use password_hash, use password_verify here
        if ($input_password === $password_from_db) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $input_username;
            $_SESSION['user_id'] = $user_id; // Set user_id for index.php
            header("location: index.php");
            exit();
        } else {
            $error_message = "Invalid username or password";
        }
    } else {
        $error_message = "Invalid username or password";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Grmelitewear - Login</title>
  <link rel="icon" href="../assets/images/logo/logo.jpg" type="image/x-icon">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      background: linear-gradient(135deg, #7a91a0ff 20%, #5c0e55ff 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      max-width: 420px;
      width: 100%;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      background: #fff;
      padding: 2rem;
      animation: fadeIn 0.8s ease-in-out;
    }
    .login-logo {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
      margin-bottom: 1rem;
    }
    @keyframes fadeIn {
      from {opacity:0; transform: translateY(-20px);}
      to {opacity:1; transform: translateY(0);}
    }
  </style>
</head>
<body>

  <div class="login-card text-center">
    <img src="../assets/images/logo/logo.jpg" alt="Logo" class="login-logo">
    <h3 class="mb-3 fw-bold">Welcome to Grmelitewear</h3>
    <p class="text-muted mb-4">Please sign in to continue</p>

    <?php if(!empty($error_message)): ?>
      <div class="alert alert-danger py-2"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        <label for="username">Username</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <label for="password">Password</label>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
    </form>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>