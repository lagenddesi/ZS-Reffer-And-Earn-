<?php
require_once __DIR__.'/../../includes/header.php';
require_once __DIR__.'/../../includes/auth.php';
requireAdmin();

$title = 'Referral Management';

$db = new Database();

// Get all referrals
$referrals = $db->query("
    SELECT r.*, 
           u1.username as referrer_username,
           u2.username as referred_username
    FROM referrals r
    JOIN users u1 ON r.referrer_id = u1.id
    JOIN users u2 ON r.referred_id = u2.id
    ORDER BY r.created_at DESC
");
?>

<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Referral System</h5>
            <a href="settings.php?tab=referral" class="btn btn-sm btn-primary">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Referrer</th>
                            <th>Referred User</th>
                            <th>Level</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($ref = $referrals->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ref['referrer_username']); ?></td>
                            <td><?php echo htmlspecialchars($ref['referred_username']); ?></td>
                            <td><?php echo $ref['level']; ?></td>
                            <td><?php echo date('d M Y', strtotime($ref['created_at'])); ?></td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Referral Earnings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Level 1 Earnings</th>
                            <th>Level 2 Earnings</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $earnings = $db->query("
                            SELECT u.username,
                                   SUM(CASE WHEN t.details LIKE '%level:1%' THEN t.amount ELSE 0 END) as level1,
                                   SUM(CASE WHEN t.details LIKE '%level:2%' THEN t.amount ELSE 0 END) as level2
                            FROM transactions t
                            JOIN users u ON t.user_id = u.id
                            WHERE t.type = 'referral'
                            GROUP BY t.user_id
                        ");
                        
                        while($earn = $earnings->fetch_assoc()):
                            $total = $earn['level1'] + $earn['level2'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($earn['username']); ?></td>
                            <td>$<?php echo number_format($earn['level1'], 2); ?></td>
                            <td>$<?php echo number_format($earn['level2'], 2); ?></td>
                            <td>$<?php echo number_format($total, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/../../includes/footer.php'; ?>
