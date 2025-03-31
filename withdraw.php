<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_login();

$user = getUser($_SESSION['user_id']);
$min_withdrawal = getSetting('min_withdrawal') ?? 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $wallet = $_POST['wallet'];
    
    if ($amount < $min_withdrawal) {
        $error = "Minimum withdrawal is $" . number_format($min_withdrawal, 2);
    } elseif ($amount > $user['balance']) {
        $error = "Insufficient balance";
    } else {
        // Record transaction
        recordTransaction($_SESSION['user_id'], $amount, 'withdrawal', $method, 'pending', 
                         "Withdrawal to $wallet");
        
        // Deduct from user balance
        updateUserBalance($_SESSION['user_id'], -$amount);
        
        header("Location: transactions.php?withdrawal=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw Funds</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Withdraw Funds</div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Amount ($)</label>
                                <input type="number" name="amount" class="form-control" 
                                       min="<?= $min_withdrawal ?>" step="0.01" required>
                                <small class="text-muted">
                                    Available: $<?= number_format($user['balance'], 2) ?> | 
                                    Min: $<?= number_format($min_withdrawal, 2) ?>
                                </small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Withdrawal Method</label>
                                <select name="method" class="form-control" required>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="trx">TRX Wallet</option>
                                    <option value="usdt">USDT (TRC20)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Wallet/Account Number</label>
                                <input type="text" name="wallet" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Request Withdrawal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
