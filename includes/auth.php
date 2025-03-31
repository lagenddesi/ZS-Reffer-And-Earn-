<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

function registerUser($data) {
    global $pdo;
    
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $referral_code = generateReferralCode();
    
    $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password, referral_code, referred_by) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['full_name'],
        $data['username'],
        $data['email'],
        $hashed_password,
        $referral_code,
        $data['referral_code'] ?? null
    ]);
    
    return $pdo->lastInsertId();
}

function loginUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    
    return false;
}

function generateReferralCode() {
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}
?>
