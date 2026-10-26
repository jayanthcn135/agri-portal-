<?php
require 'config.php';
require_login();
$user = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = intval($_POST['to_user']);
    $content = trim($_POST['content']);
    if ($to && $content !== '') {
        $stmt = $pdo->prepare("INSERT INTO messages (from_user,to_user,content,is_reply) VALUES (?,?,?,1)");
        $stmt->execute([$user['id'],$to,$content]);
    }
}
header("Location: ../dashboard.php");
exit;
