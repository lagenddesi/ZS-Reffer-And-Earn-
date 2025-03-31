<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = sha1($conn->real_escape_string($_POST['password']));
    $referral = isset($_POST['referral']) ? $conn->real_escape_string($_POST['referral']) : null;

    // Check if user exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email' OR username='$username'");
    
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "User already exists!";
        header("Location: register.php");
        exit();
    }

    // Insert new user
    $sql = "INSERT INTO users (name, email, username, password, referral_code, balance, created_at) 
            VALUES ('$name', '$email', '$username', '$password', '$referral', 0, NOW())";
    
    if ($conn->query($sql)) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Registration failed!";
        header("Location: register.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | <?php echo $site_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="text-center mb-4">Register New Account</h2>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Referral Code (Optional)</label>
            <input type="text" name="referral" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    
    <div class="text-center mt-3">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>
</body>
</html>
