<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_login();

$packages = getInvestmentPackages();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Investment Packages</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Investment Packages</h2>
        
        <div class="row">
            <?php foreach ($packages as $package): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <?= htmlspecialchars($package['name']) ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                $<?= number_format($package['min_amount'], 2) ?> - 
                                $<?= number_format($package['max_amount'], 2) ?>
                            </h5>
                            <p class="card-text">
                                <strong>ROI:</strong> <?= $package['roi_percentage'] ?>% daily<br>
                                <strong>Duration:</strong> <?= $package['duration_days'] ?> days
                            </p>
                            <a href="invest.php?package_id=<?= $package['id'] ?>" class="btn btn-success">
                                Invest Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
