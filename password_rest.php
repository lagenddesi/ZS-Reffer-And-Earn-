<?php
require_once __DIR__.'/includes/header.php';
require_once __DIR__.'/includes/auth.php';
requireGuest();

$title = 'Password Reset';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $db->escape($_POST['email']);
    
    // Check if user exists
    $user = $db->query("SELECT id FROM users WHERE email='$email' LIMIT 1")->fetch_assoc();
    
    if ($user) {
        // In a real system, you would send a password reset link via email
        $_SESSION['success'] = "Password reset instructions have been sent to your email.";
        redirect('login.php');
    } else {
        $_SESSION['error'] = "No account found with that email address.";
    }
}
?>

<div class="container mt-5" style="max-width: 500px;">
    <h2 class="text-center mb-4">Reset Password</h2>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
    
    <div class="text-center mt-3">
        Remember your password? <a href="login.php">Login here</a>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
