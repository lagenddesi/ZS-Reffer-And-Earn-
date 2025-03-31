<?php
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Get investment packages
$packages = $conn->query("SELECT * FROM investment_packages WHERE status=1");

// Get transactions (limited to 10 for performance)
$transactions = $conn->query("SELECT * FROM transactions WHERE user_id=$user_id ORDER BY id DESC LIMIT 10");

// Get referral count
$referrals = $conn->query("SELECT COUNT(id) as count FROM users WHERE referral_code='".$user['username']."'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | <?php echo $site_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><?php echo $site_name; ?></a>
        <div class="ml-auto">
            <span class="text-light me-3">Welcome, <?php echo $user['username']; ?></span>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <!-- Balance Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Account Balance</h5>
                    <h2>$<?php echo number_format($user['balance'], 2); ?></h2>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="deposit.php" class="btn btn-sm btn-success">Deposit</a>
                        <a href="withdraw.php" class="btn btn-sm btn-primary">Withdraw</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Referral Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Referral Program</h5>
                    <p>Total Referrals: <?php echo $referrals['count']; ?></p>
                    <div class="input-group mb-3">
                        <input type="text" id="refLink" class="form-control" value="<?php echo $base_url; ?>register.php?ref=<?php echo $user['username']; ?>" readonly>
                        <button class="btn btn-outline-secondary" onclick="copyRefLink()">Copy</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Investment Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Investments</h5>
                    <?php
                    $active_investments = $conn->query("SELECT COUNT(id) as count FROM user_investments WHERE user_id=$user_id AND status=1")->fetch_assoc();
                    ?>
                    <p>Active Packages: <?php echo $active_investments['count']; ?></p>
                    <a href="invest.php" class="btn btn-sm btn-warning">Invest Now</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Investment Packages -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Investment Packages</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <?php while($package = $packages->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5><?php echo $package['name']; ?></h5>
                            <p>ROI: <?php echo $package['roi']; ?>%</p>
                            <p>Duration: <?php echo $package['duration']; ?> days</p>
                            <p>Min: $<?php echo $package['min_amount']; ?></p>
                            <a href="invest.php?package=<?php echo $package['id']; ?>" class="btn btn-sm btn-primary">Invest</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <h4>Recent Transactions</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($txn = $transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($txn['created_at'])); ?></td>
                        <td><?php echo ucfirst($txn['type']); ?></td>
                        <td>$<?php echo number_format($txn['amount'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $txn['status'] == 'approved' ? 'success' : 
                                     ($txn['status'] == 'pending' ? 'warning' : 'danger'); 
                            ?>">
                                <?php echo ucfirst($txn['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="transactions.php" class="btn btn-sm btn-secondary">View All</a>
        </div>
    </div>
</div>

<script>
function copyRefLink() {
    var copyText = document.getElementById("refLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Referral link copied!");
}
</script>
</body>
</html>
