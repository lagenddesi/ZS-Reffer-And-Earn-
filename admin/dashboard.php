<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_admin();

$totalUsers = getTotalUsers();
$activeInvestments = getActiveInvestmentsCount();
$pendingTransactions = getPendingTransactionsCount();
$totalDeposits = getTotalDeposits();
$totalWithdrawals = getTotalWithdrawals();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin-sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Users</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $totalUsers ?></h5>
                                <a href="users.php" class="text-white">View Users</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Active Investments</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $activeInvestments ?></h5>
                                <a href="packages.php" class="text-white">Manage Packages</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Pending Transactions</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $pendingTransactions ?></h5>
                                <a href="transactions.php" class="text-white">Review Transactions</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Recent Deposits</h5>
                            </div>
                            <div class="card-body">
                                <?php $recentDeposits = getRecentTransactions('deposit', 5); ?>
                                <?php if (count($recentDeposits) > 0): ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentDeposits as $deposit): ?>
                                                <tr>
                                                    <td><?= getUserName($deposit['user_id']) ?></td>
                                                    <td>$<?= number_format($deposit['amount'], 2) ?></td>
                                                    <td><?= date('M d, Y', strtotime($deposit['created_at'])) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= getStatusBadge($deposit['status']) ?>">
                                                            <?= ucfirst($deposit['status']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>No recent deposits</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Recent Withdrawals</h5>
                            </div>
                            <div class="card-body">
                                <?php $recentWithdrawals = getRecentTransactions('withdrawal', 5); ?>
                                <?php if (count($recentWithdrawals) > 0): ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentWithdrawals as $withdrawal): ?>
                                                <tr>
                                                    <td><?= getUserName($withdrawal['user_id']) ?></td>
                                                    <td>$<?= number_format($withdrawal['amount'], 2) ?></td>
                                                    <td><?= date('M d, Y', strtotime($withdrawal['created_at'])) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= getStatusBadge($withdrawal['status']) ?>">
                                                            <?= ucfirst($withdrawal['status']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>No recent withdrawals</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5>System Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Total Deposits</h6>
                                <p>$<?= number_format($totalDeposits, 2) ?></p>
                            </div>
                            <div class="col-md-4">
                                <h6>Total Withdrawals</h6>
                                <p>$<?= number_format($totalWithdrawals, 2) ?></p>
                            </div>
                            <div class="col-md-4">
                                <h6>System Profit</h6>
                                <p>$<?= number_format($totalDeposits - $totalWithdrawals, 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
