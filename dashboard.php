<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_login();

$user = getUser($_SESSION['user_id']);
$investments = getUserInvestments($_SESSION['user_id']);
$referrals = getUserReferrals($_SESSION['user_id']);
$transactions = getUserTransactions($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Investment Platform</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Account Balance
                    </div>
                    <div class="card-body">
                        <h3>$<?= number_format($user['balance'], 2) ?></h3>
                        <a href="deposit.php" class="btn btn-success">Deposit</a>
                        <a href="withdraw.php" class="btn btn-primary">Withdraw</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Active Investments
                    </div>
                    <div class="card-body">
                        <?php if (count($investments) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Daily Profit</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($investments as $investment): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($investment['package_name']) ?></td>
                                            <td>$<?= number_format($investment['amount'], 2) ?></td>
                                            <td>$<?= number_format($investment['daily_profit'], 2) ?></td>
                                            <td><?= date('M d, Y', strtotime($investment['end_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>You don't have any active investments.</p>
                            <a href="packages.php" class="btn btn-primary">Invest Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Referral Program
                    </div>
                    <div class="card-body">
                        <p>Your referral link:</p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="referralLink" 
                                   value="<?= "https://yourdomain.com/register.php?ref=" . $user['referral_code'] ?>" readonly>
                            <button class="btn btn-outline-secondary" onclick="copyReferralLink()">Copy</button>
                        </div>
                        <p>Total referrals: <?= count($referrals) ?></p>
                        <a href="referrals.php" class="btn btn-info">View Referrals</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Recent Transactions
                    </div>
                    <div class="card-body">
                        <?php if (count($transactions) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($transactions, 0, 5) as $transaction): ?>
                                        <tr>
                                            <td><?= date('M d', strtotime($transaction['created_at'])) ?></td>
                                            <td>$<?= number_format($transaction['amount'], 2) ?></td>
                                            <td><?= ucfirst($transaction['type']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= getStatusBadge($transaction['status']) ?>">
                                                    <?= ucfirst($transaction['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="transactions.php" class="btn btn-sm btn-outline-primary">View All</a>
                        <?php else: ?>
                            <p>No transactions yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyReferralLink() {
            var copyText = document.getElementById("referralLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Referral link copied!");
        }
    </script>
</body>
</html>
