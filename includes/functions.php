<?php
function getUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function getInvestmentPackages() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM packages WHERE is_active = TRUE");
    return $stmt->fetchAll();
}

function recordTransaction($user_id, $amount, $type, $method, $status, $details = '', $proof = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO transactions 
                          (user_id, amount, type, method, status, details, proof_path)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $amount, $type, $method, $status, $details, $proof]);
    
    return $pdo->lastInsertId();
}

function getStatusBadge($status) {
    switch ($status) {
        case 'pending': return 'warning';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

// ... (Add more functions as needed)
?>
