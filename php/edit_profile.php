<?php
require 'config.php';
require_login();
$user = $_SESSION['user'];
$uid = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $stmt = $pdo->prepare("UPDATE users SET name = ?, mobile = ? WHERE id = ?");
    $stmt->execute([$name, $mobile, $uid]);

    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$uid]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($r && $r['role'] === 'farmer') {
        $dob = $_POST['dob'] ?? null;
        $pin = $_POST['pin_code'] ?? null;
        $village = $_POST['village'] ?? null;
        $taluk = $_POST['taluk'] ?? null;
        $address = $_POST['address'] ?? null;

        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $imgName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dest = __DIR__ . '/../uploads/' . $imgName;
            if (!is_dir(__DIR__ . '/../uploads')) mkdir(__DIR__ . '/../uploads', 0755, true);
            move_uploaded_file($_FILES['photo']['tmp_name'], $dest);
            $stmt2 = $pdo->prepare("UPDATE farmer_details SET photo = ? WHERE user_id = ?");
            $stmt2->execute([$imgName, $uid]);
        }
        $stmt = $pdo->prepare("SELECT id FROM farmer_details WHERE user_id = ?");
        $stmt->execute([$uid]);
        if ($stmt->fetch()) {
            $stmt = $pdo->prepare("UPDATE farmer_details SET dob=?, pin_code=?, village=?, taluk=?, address=? WHERE user_id = ?");
            $stmt->execute([$dob, $pin, $village, $taluk, $address, $uid]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO farmer_details (user_id,dob,pin_code,village,taluk,address) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$uid,$dob,$pin,$village,$taluk,$address]);
        }
    } else {
        $email = $_POST['email'] ?? null;
        if ($email) {
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$email, $uid]);
        }
    }
}
header("Location: ../profile.php");
exit;
