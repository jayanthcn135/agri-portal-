<?php
// php/config.php
session_start();

$DB_HOST = '127.0.0.1';
$DB_NAME = 'agritech';
$DB_USER = 'root';
$DB_PASS = ''; // set DB password if any

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    die("DB connection failed: " . $e->getMessage());
}

function require_login() {
    if (!isset($_SESSION['user'])) {
        header("Location: /login.php");
        exit;
    }
}
?>