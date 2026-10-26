<?php
require 'php/config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($mobile === '' || $password === '') {
        $err = 'Provide mobile and password';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE mobile = ? LIMIT 1");
        $stmt->execute([$mobile]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($u && password_verify($password, $u['password'])) {
            $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
            header("Location: dashboard.php");
            exit;
        } else {
            $err = 'Invalid credentials';
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login - AgriTech</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar"><div class="logo">AgriTech</div></header>
<main class="container">
  <div style="max-width:420px;margin:30px auto;background:#fff;padding:18px;border-radius:10px">
    <h2>Login</h2>
    <?php if($err): ?><div style="background:#ffe6e6;padding:8px;border-radius:6px"><?php echo htmlspecialchars($err) ?></div><?php endif; ?>
    <form method="post">
      <label>Mobile</label>
      <input name="mobile" required>
      <label>Password</label>
      <input name="password" type="password" required>
      <div style="margin-top:12px">
        <button class="btn" type="submit">Login</button>
        <a href="signup.php" style="margin-left:10px">Sign up</a>
      </div>
    </form>
  </div>
</main>
</body>
</html>
