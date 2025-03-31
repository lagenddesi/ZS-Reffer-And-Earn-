<?php
$host = "sqlXXX.infinityfree.com";
$username = "if0_XXXXXXX";
$password = "YOUR_PASSWORD";
$dbname = "if0_XXXXXXX";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
