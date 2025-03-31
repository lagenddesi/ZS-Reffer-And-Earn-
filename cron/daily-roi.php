<?php
// Since InfinityFree doesn't support cron jobs, we'll handle ROI calculation differently
// This file would be called from dashboard.php when users login

require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/config.php';

$db = new Database();

// Get all active investments that need ROI calculation
$investments = $db->query("
    SELECT ui.*, u.balance 
    FROM user_investments ui
    JOIN users u ON ui.user_id = u.id
    WHERE ui.status = 1 
    AND ui.end_date >= CURDATE()
");

while($investment = $investments->fetch_assoc()) {
    // Calculate daily ROI
    $daily_roi = ($investment['amount'] * $investment['roi'] / 100) / $investment['duration'];
    
    // Add to user balance
    $db->query("UPDATE users SET balance = balance + $daily_roi WHERE id = ".$investment['user_id']);
    
    // Record transaction
    $db->query("INSERT INTO transactions 
               (user_id, type, amount, details, status, created_at)
               VALUES 
               (".$investment['user_id'].", 'earning', $daily_roi, 
               'Daily ROI for investment #".$investment['id']."', 'approved', NOW())");
    
    // Update investment (mark last payout)
    $db->query("UPDATE user_investments SET updated_at = NOW() WHERE id = ".$investment['id']);
}
