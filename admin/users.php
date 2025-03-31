<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_admin();

$users = getAllUsers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/admin-sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Users</h1>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Balance</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>$<?= number_format($user['balance'], 2) ?></td>
                                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $user['is_active'] ? 'Active' : 'Banned' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="user-edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <?php if ($user['is_active']): ?>
                                            <a href="user-ban.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Ban</a>
                                        <?php else: ?>
                                            <a href="user-unban.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-success">Unban</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
