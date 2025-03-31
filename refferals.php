// referrals.php
function processReferral($referred_user_id) {
    global $pdo;
    
    // Get the user who referred this new user
    $stmt = $pdo->prepare("SELECT referred_by FROM users WHERE id = ?");
    $stmt->execute([$referred_user_id]);
    $user = $stmt->fetch();
    
    if (!$user || !$user['referred_by']) return;
    
    $referrer_id = $user['referred_by'];
    $levels = getReferralLevels();
    
    // Process multi-level referral
    foreach ($levels as $level => $commission) {
        if ($level > 1) {
            // For levels beyond 1, find the referrer of the referrer
            $stmt = $pdo->prepare("SELECT referred_by FROM users WHERE id = ?");
            $stmt->execute([$referrer_id]);
            $referrer = $stmt->fetch();
            $referrer_id = $referrer ? $referrer['referred_by'] : null;
            
            if (!$referrer_id) break;
        }
        
        // Calculate commission amount (example: 5% of first deposit)
        $commission_amount = 0; // You would calculate this based on your business logic
        
        // Record referral commission
        $stmt = $pdo->prepare("INSERT INTO referrals 
                              (referrer_id, referred_id, level, commission_amount)
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$referrer_id, $referred_user_id, $level, $commission_amount]);
        
        // Update referrer's balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$commission_amount, $referrer_id]);
        
        // Record transaction
        recordTransaction($referrer_id, $commission_amount, 'referral', 'system', 'approved', 
                         "Level $level referral commission");
    }
}

function getReferralLevels() {
    // Example: 3-level referral system with 10%, 5%, 2% commissions
    return [
        1 => 10, // Level 1: 10%
        2 => 5,  // Level 2: 5%
        3 => 2   // Level 3: 2%
    ];
}
