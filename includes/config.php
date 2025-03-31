<?php
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session settings
session_start();

// Base URL
define('BASE_URL', 'https://yourdomain.com');

// Timezone
date_default_timezone_set('Asia/Karachi');

// Database connection
require_once 'db.php';

// Other settings
define('SITE_NAME', 'Investment Platform');
define('ADMIN_EMAIL', 'admin@yourdomain.com');
