<?php
require_once __DIR__.'/../../includes/header.php';
require_once __DIR__.'/../../includes/auth.php';
requireAdmin();

$title = 'Investment Packages';

$db = new Database();

// Add new package
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_package'])) {
    $name = $db->escape($_POST['name']);
    $roi = $db->escape($_POST['roi']);
    $duration = $db->escape($_POST['duration']);
    $min_amount = $db->escape($_POST['min_amount']);
    $max_amount = $db->escape($_POST['max_amount']);
    
    $db->query("INSERT INTO investment_packages 
               (name, roi, duration, min_amount, max_amount, created_at) 
               VALUES ('$name', '$roi', '$duration', '$min_amount', 
               ".($max_amount ? "'$max_amount'" : "NULL").", NOW())");
    
    $_SESSION['success'] = "Package added successfully!";
    redirect('packages.php');
}

// Toggle package status
if (isset($_GET['toggle_status'])) {
    $id = $db->escape($_GET['toggle_status']);
    $db->query("UPDATE investment_packages SET status = NOT status WHERE id='$id'");
    redirect('packages.php');
}

// Get all packages
$packages = $db->query("SELECT * FROM investment_packages ORDER BY status DESC, id DESC");
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Current Packages</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ROI</th>
                                    <th>Duration</th>
                                    <th>Min Amount</th>
                                    <th>Max Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($pkg = $packages->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pkg['name']); ?></td>
                                    <td><?php echo $pkg['roi']; ?>%</td>
                                    <td><?php echo $pkg['duration']; ?> days</td>
                                    <td>$<?php echo number_format($pkg['min_amount'], 2); ?></td>
                                    <td><?php echo $pkg['max_amount'] ? '$'.number_format($pkg['max_amount'], 2) : 'Unlimited'; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $pkg['status'] ? 'success' : 'danger'; ?>">
                                            <?php echo $pkg['status'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="packages.php?toggle_status=<?php echo $pkg['id']; ?>" 
                                           class="btn btn-sm btn-<?php echo $pkg['status'] ? 'warning' : 'success'; ?>">
                                            <?php echo $pkg['status'] ? 'Deactivate' : 'Activate'; ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Package Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ROI Percentage</label>
                        <input type="number" name="roi" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" name="duration" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Amount ($)</label>
                        <input type="number" name="min_amount" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Maximum Amount ($) <small class="text-muted">(Leave empty for unlimited)</small></label>
                        <input type="number" name="max_amount" step="0.01" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_package" class="btn btn-primary">Save Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/../../includes/footer.php'; ?>
