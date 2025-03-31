<?php
require_once 'auth.php';

function require_admin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit;
    }
    
    // In a real app, you would check if user is admin
    // For simplicity, we'll just check if logged in
}
?>
