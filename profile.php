<?php
require 'php/config.php';
require_login();
$user = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

$fd = false;
if ($u['role'] === 'farmer') {
    $stmt = $pdo->prepare("SELECT * FROM farmer_details WHERE user_id = ?");
    $stmt->execute([$u['id']]);
    $fd = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Profile</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<header class="topbar"><div class="logo">AgriTech</div><nav class="nav"><a href="dashboard.php">Dashboard</a><a href="php/logout.php">Logout</a></nav></header>

<main class="container app">
  <aside class="sidebar">
    <h3><?php echo htmlspecialchars($u['name']) ?></h3>
    <img id="photoPreview" class="profile-photo" src="<?php echo $fd && $fd['photo'] ? 'uploads/'.htmlspecialchars($fd['photo']) : 'assets/default-avatar.png' ?>" alt="photo">
    <p class="small">Mobile: <?php echo htmlspecialchars($u['mobile']) ?></p>
    <p class="small">Role: <?php echo htmlspecialchars($u['role']) ?></p>
  </aside>

  <section class="main">
    <h2>Edit Profile</h2>
    <form action="php/edit_profile.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="user_id" value="<?php echo $u['id'] ?>">
      <div class="row">
        <div style="flex:1"><label>Name</label><input name="name" value="<?php echo htmlspecialchars($u['name']) ?>"></div>
        <div style="flex:1"><label>Mobile</label><input name="mobile" value="<?php echo htmlspecialchars($u['mobile']) ?>"></div>
      </div>

      <?php if($u['role'] === 'farmer'): ?>
        <div class="row">
          <div style="flex:1"><label>DOB</label><input type="date" name="dob" value="<?php echo htmlspecialchars($fd['dob'] ?? '') ?>"></div>
          <div style="flex:1"><label>Pin code</label><input name="pin_code" value="<?php echo htmlspecialchars($fd['pin_code'] ?? '') ?>"></div>
        </div>
        <div class="row">
          <div style="flex:1"><label>Village</label><input name="village" value="<?php echo htmlspecialchars($fd['village'] ?? '') ?>"></div>
          <div style="flex:1"><label>Taluk</label><input name="taluk" value="<?php echo htmlspecialchars($fd['taluk'] ?? '') ?>"></div>
        </div>
        <label>Address</label>
        <textarea name="address"><?php echo htmlspecialchars($fd['address'] ?? '') ?></textarea>
        <label>Photo (upload or take from camera)</label>
        <input id="photoInput" name="photo" type="file" accept="image/*" capture="environment">
      <?php else: ?>
        <label>Email</label>
        <input name="email" value="<?php echo htmlspecialchars($u['email']) ?>">
      <?php endif; ?>

      <div style="margin-top:12px">
        <button class="btn" type="submit">Save details</button>
        <a href="php/logout.php" style="margin-left:8px">Logout</a>
      </div>
    </form>
  </section>
</main>

<script src="js/main.js"></script>
</body>
</html>
