<?php
require 'config.php';
require_login();
$user = $_SESSION['user'];
if ($user['role'] !== 'farmer') {
    header("Location: ../dashboard.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $farmer_id = $user['id'];
    $crop = $_POST['crop_name'] ?? '';
    $days = $_POST['days'] ?? 0;
    $soil = $_POST['soil_type'] ?? '';
    $temperature = $_POST['temperature'] ?? null;
    $moisture = $_POST['moisture'] ?? null;
    $npk = $_POST['npk'] ?? '';
    $humidity = $_POST['humidity'] ?? null;

    $imgName = null;
    if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = __DIR__ . '/../uploads/' . $imgName;
        if (!is_dir(__DIR__ . '/../uploads')) mkdir(__DIR__ . '/../uploads', 0755, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    }

    $stmt = $pdo->prepare("INSERT INTO records (farmer_id,crop_name,days_after_plantation,soil_type,temperature,moisture,npk,humidity,image) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$farmer_id,$crop,$days,$soil,$temperature,$moisture,$npk,$humidity,$imgName]);
}
header("Location: ../dashboard.php");
exit;
