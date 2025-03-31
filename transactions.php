// transactions.php
function processDailyROI() {
    global $pdo;
    
    // Get all active investments
    $stmt = $pdo->query("SELECT * FROM user_investments WHERE status = 'active' AND end_date >= CURDATE()");
    $investments = $stmt->fetchAll();
    
    foreach ($investments as $investment) {
        // Add daily profit to user balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$investment['daily_profit'], $investment['user_id']]);
        
        // Update investment total profit
        $stmt = $pdo->prepare("UPDATE user_investments 
                              SET total_profit = total_profit + ? 
                              WHERE id = ?");
        $stmt->execute([$investment['daily_profit'], $investment['id']]);
        
        // Record profit transaction
        recordTransaction($investment['user_id'], $investment['daily_profit'], 
                         'profit', 'system', 'approved', 
                         "Daily ROI for investment #{$investment['id']}");
        
        // Check if investment has completed
        if (date('Y-m-d') >= $investment['end_date']) {
            $stmt = $pdo->prepare("UPDATE user_investments SET status = 'completed' WHERE id = ?");
            $stmt->execute([$investment['id']]);
        }
    }
}
