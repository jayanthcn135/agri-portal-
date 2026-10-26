<?php
require 'php/config.php';

$role = isset($_GET['role']) && in_array($_GET['role'], ['farmer','member']) ? $_GET['role'] : 'farmer';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'farmer';
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');

    if ($name === '') $errors[] = 'Name required';
    if ($mobile === '') $errors[] = 'Mobile required';
    if (strlen($password) < 6) $errors[] = 'Password min 6 chars';
    if ($role === 'member' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required for member';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE mobile = ? LIMIT 1");
        $stmt->execute([$mobile]);
        if ($stmt->fetch()) {
            $errors[] = 'Mobile already registered';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,mobile,password,role) VALUES (?,?,?,?,?)");
            $stmt->execute([$name, $email ?: null, $mobile, $hash, $role]);
            $userId = $pdo->lastInsertId();
            if ($role === 'farmer') {
                $pin = $_POST['pin_code'] ?? null;
                $village = $_POST['village'] ?? null;
                $taluk = $_POST['taluk'] ?? null;
                $stmt = $pdo->prepare("INSERT INTO farmer_details (user_id,pin_code,village,taluk) VALUES (?,?,?,?)");
                $stmt->execute([$userId, $pin, $village, $taluk]);
            }
            $_SESSION['user'] = ['id'=>$userId, 'name'=>$name, 'role'=>$role];
            header("Location: dashboard.php");
            exit;
        }
    }
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign Up - AgriTech</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
  <div class="logo">AgriTech</div>
  <nav class="nav"><a href="index.php">Home</a></nav>
</header>

<main class="container">
  <div style="max-width:800px;margin:20px auto;background:#fff;padding:18px;border-radius:10px">
    <h2>Create account (<?php echo htmlspecialchars($role) ?>)</h2>

    <?php if($errors): ?>
      <div style="background:#ffe6e6;padding:10px;border-radius:6px;margin-bottom:10px">
        <?php foreach($errors as $e) echo "<div>$e</div>"; ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="role" value="<?php echo htmlspecialchars($role) ?>">
      <div class="row">
        <div style="flex:1">
          <label>Name</label>
          <input name="name" required>
        </div>
        <div style="flex:1">
          <label>Mobile</label>
          <input name="mobile" required pattern="[0-9]+" maxlength="15">
        </div>
      </div>

      <?php if($role === 'member'): ?>
        <label>Email</label>
        <input name="email" type="email" required>
      <?php endif; ?>

      <div class="row">
        <div style="flex:1">
          <label>Password</label>
          <input name="password" type="password" required minlength="6">
        </div>
      </div>

      <?php if($role === 'farmer'): ?>
        <div class="row">
          <div style="flex:1">
            <label>Pin code</label>
            <input name="pin_code">
          </div>
          <div style="flex:1">
            <label>Village</label>
            <input name="village">
          </div>
          <div style="flex:1">
            <label>Taluk</label>
            <input name="taluk">
          </div>
        </div>
      <?php endif; ?>

      <div style="margin-top:12px">
        <button class="btn" type="submit">Create account</button>
        <a href="login.php" style="margin-left:10px">Already have account?</a>
      </div>
    </form>
  </div>
</main>
</body>
</html>
