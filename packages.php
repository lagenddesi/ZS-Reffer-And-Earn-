// packages.php
function getInvestmentPackages() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM packages WHERE is_active = TRUE");
    return $stmt->fetchAll();
}

function createInvestment($user_id, $package_id, $amount) {
    global $pdo;
    
    // Get package details
    $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->execute([$package_id]);
    $package = $stmt->fetch();
    
    if (!$package) return false;
    
    // Calculate daily profit
    $daily_profit = ($amount * $package['roi_percentage']) / 100;
    
    // Calculate end date
    $end_date = date('Y-m-d', strtotime("+{$package['duration_days']} days"));
    
    // Create investment
    $stmt = $pdo->prepare("INSERT INTO user_investments 
                          (user_id, package_id, amount, start_date, end_date, daily_profit)
                          VALUES (?, ?, ?, CURDATE(), ?, ?)");
    $stmt->execute([$user_id, $package_id, $amount, $end_date, $daily_profit]);
    
    // Deduct amount from user balance
    $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$amount, $user_id]);
    
    // Record transaction
    recordTransaction($user_id, $amount, 'investment', 'system', 'approved', "Investment in {$package['name']}");
    
    return $pdo->lastInsertId();
}
