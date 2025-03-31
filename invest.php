<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_login();

$package_id = $_GET['package_id'] ?? null;
$package = getPackageById($package_id);

if (!$package) {
    header("Location: packages.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    
    if ($amount >= $package['min_amount'] && $amount <= $package['max_amount']) {
        createInvestment($_SESSION['user_id'], $package['id'], $amount);
        header("Location: dashboard.php?invested=1");
        exit;
    } else {
        $error = "Amount must be between $" . number_format($package['min_amount'], 2) . 
                 " and $" . number_format($package['max_amount'], 2);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invest in <?= htmlspecialchars($package['name']) ?></title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Invest in <?= htmlspecialchars($package['name']) ?>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Investment Amount ($)</label>
                                <input type="number" name="amount" class="form-control" 
                                       min="<?= $package['min_amount'] ?>" 
                                       max="<?= $package['max_amount'] ?>" 
                                       step="0.01" required>
                                <small class="text-muted">
                                    Min: $<?= number_format($package['min_amount'], 2) ?> | 
                                    Max: $<?= number_format($package['max_amount'], 2) ?>
                                </small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Expected Daily Profit</label>
                                <input type="text" class="form-control" readonly 
                                       id="dailyProfitPreview">
                            </div>
                            <button type="submit" class="btn btn-primary">Confirm Investment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('input[name="amount"]').addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            const dailyProfit = (amount * <?= $package['roi_percentage'] ?>) / 100;
            document.getElementById('dailyProfitPreview').value = '$' + dailyProfit.toFixed(2);
        });
    </script>
</body>
</html>
