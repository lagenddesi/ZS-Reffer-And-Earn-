<?php
// Database configuration for InfinityFree
define('DB_HOST', 'sqlXXX.infinityfree.com');
define('DB_USER', 'if0_XXXXXX');
define('DB_PASS', 'your_password');
define('DB_NAME', 'if0_XXXXXX');

// Establish connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// Basic site settings
$site_name = "InvestPro";
$base_url = "http://your-site.epizy.com/"; // Change to your InfinityFree URL
?>
