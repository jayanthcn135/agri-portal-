<?php
require 'php/config.php';
require_login();

$user = $_SESSION['user'];
if ($user['role'] === 'farmer') {
    include 'farmer_dashboard.php';
    exit;
} else {
    include 'member_dashboard.php';
    exit;
}
