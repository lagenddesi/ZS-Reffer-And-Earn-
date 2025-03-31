<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_admin();

$pending_deposits = getPendingTransactions('deposit');
$pending_withdrawals = getPendingTransactions('withdrawal');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transactions - Admin</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin-sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Pending Transactions</h1>
                </div>
                
                <ul class="nav nav-tabs" id="transactionTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#deposits">Deposits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#withdrawals">Withdrawals</a>
                    </li>
                </ul>
                
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="deposits">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Proof</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pending_deposits as $tx): ?>
                                        <tr>
                                            <td><?= $tx['id'] ?></td>
                                            <td><?= getUserName($tx['user_id']) ?></td>
                                            <td>$<?= number_format($tx['amount'], 2) ?></td>
                                            <td><?= ucfirst($tx['method']) ?></td>
                                            <td><?= date('M d, Y', strtotime($tx['created_at'])) ?></td>
                                            <td>
                                                <?php if ($tx['details']): ?>
                                                    <a href="<?= $tx['details'] ?>" target="_blank" class="btn btn-sm btn-info">View</a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="approve-tx.php?id=<?= $tx['id'] ?>&type=deposit" class="btn btn-sm btn-success">Approve</a>
                                                <a href="reject-tx.php?id=<?= $tx['id'] ?>&type=deposit" class="btn btn-sm btn-danger">Reject</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="withdrawals">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Wallet/Account</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pending_withdrawals as $tx): ?>
                                        <tr>
                                            <td><?= $tx['id'] ?></td>
                                            <td><?= getUserName($tx['user_id']) ?></td>
                                            <td>$<?= number_format($tx['amount'], 2) ?></td>
                                            <td><?= ucfirst($tx['method']) ?></td>
                                            <td><?= $tx['details'] ?></td>
                                            <td><?= date('M d, Y', strtotime($tx['created_at'])) ?></td>
                                            <td>
                                                <a href="approve-tx.php?id=<?= $tx['id'] ?>&type=withdrawal" class="btn btn-sm btn-success">Approve</a>
                                                <a href="reject-tx.php?id=<?= $tx['id'] ?>&type=withdrawal" class="btn btn-sm btn-danger">Reject</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
