<?php
require_once __DIR__.'/../../includes/header.php';
require_once __DIR__.'/../../includes/auth.php';
requireAdmin();

$title = 'System Settings';
$db = new Database();

// Active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

// Save settings
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        $key = $db->escape($key);
        $value = $db->escape($value);
        
        $db->query("INSERT INTO system_settings (`key`, `value`, `updated_at`) 
                   VALUES ('$key', '$value', NOW())
                   ON DUPLICATE KEY UPDATE `value`='$value', `updated_at`=NOW()");
    }
    $_SESSION['success'] = "Settings updated successfully!";
    redirect('settings.php?tab='.$active_tab);
}

// Get all settings
$settings_result = $db->query("SELECT * FROM system_settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['key']] = $row['value'];
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= $active_tab == 'general' ? 'active' : '' ?>" href="settings.php?tab=general">
                                <i class="fas fa-cog me-2"></i> General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_tab == 'referral' ? 'active' : '' ?>" href="settings.php?tab=referral">
                                <i class="fas fa-users me-2"></i> Referral
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_tab == 'payment' ? 'active' : '' ?>" href="settings.php?tab=payment">
                                <i class="fas fa-money-bill-wave me-2"></i> Payment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_tab == 'email' ? 'active' : '' ?>" href="settings.php?tab=email">
                                <i class="fas fa-envelope me-2"></i> Email
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <?php if($active_tab == 'general'): ?>
                            <h5 class="mb-4"><i class="fas fa-cog me-2"></i> General Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Site Name</label>
                                <input type="text" name="site_name" class="form-control" value="<?= $settings['site_name'] ?? SITE_NAME ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control" value="<?= $settings['contact_email'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Default Currency</label>
                                <select name="currency" class="form-select">
                                    <option value="$" <?= ($settings['currency'] ?? '$') == '$' ? 'selected' : '' ?>>Dollar ($)</option>
                                    <option value="₹" <?= ($settings['currency'] ?? '$') == '₹' ? 'selected' : '' ?>>Rupee (₹)</option>
                                    <option value="€" <?= ($settings['currency'] ?? '$') == '€' ? 'selected' : '' ?>>Euro (€)</option>
                                </select>
                            </div>

                        <?php elseif($active_tab == 'referral'): ?>
                            <h5 class="mb-4"><i class="fas fa-users me-2"></i> Referral Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Level 1 Commission (%)</label>
                                <input type="number" name="referral_level1" class="form-control" value="<?= $settings['referral_level1'] ?? 5 ?>" min="0" max="100" step="0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level 2 Commission (%)</label>
                                <input type="number" name="referral_level2" class="form-control" value="<?= $settings['referral_level2'] ?? 3 ?>" min="0" max="100" step="0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Minimum Withdrawal for Referral Earnings</label>
                                <input type="number" name="referral_min_withdrawal" class="form-control" value="<?= $settings['referral_min_withdrawal'] ?? 10 ?>" min="0" step="0.01">
                            </div>

                        <?php elseif($active_tab == 'payment'): ?>
                            <h5 class="mb-4"><i class="fas fa-money-bill-wave me-2"></i> Payment Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Minimum Deposit Amount</label>
                                <input type="number" name="min_deposit" class="form-control" value="<?= $settings['min_deposit'] ?? 10 ?>" min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Minimum Withdrawal Amount</label>
                                <input type="number" name="min_withdrawal" class="form-control" value="<?= $settings['min_withdrawal'] ?? 10 ?>" min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Withdrawal Fee (%)</label>
                                <input type="number" name="withdrawal_fee" class="form-control" value="<?= $settings['withdrawal_fee'] ?? 0 ?>" min="0" max="10" step="0.1">
                            </div>

                        <?php elseif($active_tab == 'email'): ?>
                            <h5 class="mb-4"><i class="fas fa-envelope me-2"></i> Email Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">SMTP Host</label>
                                <input type="text" name="smtp_host" class="form-control" value="<?= $settings['smtp_host'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Port</label>
                                <input type="number" name="smtp_port" class="form-control" value="<?= $settings['smtp_port'] ?? 587 ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Username</label>
                                <input type="text" name="smtp_username" class="form-control" value="<?= $settings['smtp_username'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Password</label>
                                <input type="password" name="smtp_password" class="form-control" value="<?= $settings['smtp_password'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">From Email</label>
                                <input type="email" name="from_email" class="form-control" value="<?= $settings['from_email'] ?? 'noreply@'.strtolower(SITE_NAME).'.com' ?>">
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/../../includes/footer.php'; ?>
