<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $proof = $_FILES['proof'];
    
    // Save proof image
    $proof_path = 'uploads/proofs/' . uniqid() . '_' . $proof['name'];
    move_uploaded_file($proof['tmp_name'], $proof_path);
    
    // Record transaction
    recordTransaction($_SESSION['user_id'], $amount, 'deposit', $method, 'pending', 
                     "Manual deposit via $method", $proof_path);
    
    header("Location: transactions.php?deposited=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deposit Funds</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Deposit Funds</div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Amount ($)</label>
                                <input type="number" name="amount" class="form-control" min="10" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="method" class="form-control" required>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="easypaisa">EasyPaisa</option>
                                    <option value="jazzcash">JazzCash</option>
                                    <option value="trx">TRX (TRON)</option>
                                    <option value="usdt">USDT (TRC20)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Proof (Screenshot/Receipt)</label>
                                <input type="file" name="proof" class="form-control" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Deposit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
