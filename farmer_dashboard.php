<?php
require 'php/config.php';
$user = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT fd.* FROM farmer_details fd WHERE fd.user_id = ?");
$stmt->execute([$user['id']]);
$fd = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM records WHERE farmer_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Farmer Dashboard</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<header class="topbar">
  <div class="logo">AgriTech</div>
  <nav class="nav">
    <a href="profile.php">Profile</a>
    <a href="php/logout.php">Logout</a>
  </nav>
</header>

<main class="container app">
  <aside class="sidebar">
    <h3>Welcome, <?php echo htmlspecialchars($user['name']) ?></h3>
    <img class="profile-photo" src="<?php echo $fd && $fd['photo'] ? 'uploads/'.$fd['photo'] : 'assets/default-avatar.png' ?>" alt="photo">
    <p class="small">Village: <?php echo htmlspecialchars($fd['village'] ?? '-') ?></p>
    <div style="margin-top:12px">
      <a href="profile.php" class="btn-ghost">Edit Profile</a>
    </div>
  </aside>

  <section class="main">
    <h2>Farmer Dashboard</h2>

    <h3>Create a new crop record</h3>
    <form action="php/create_record.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="farmer_id" value="<?php echo $user['id'] ?>">
      <div class="row">
        <div style="flex:1"><label>Crop name</label><input name="crop_name" required></div>
        <div style="flex:1"><label>Days since plantation</label><input name="days" type="number" min="0"></div>
      </div>
      <div class="row">
        <div style="flex:1"><label>Type of soil</label><input name="soil_type"></div>
        <div style="flex:1"><label>Temperature (°C)</label><input name="temperature" type="number" step="0.1"></div>
      </div>
      <div class="row">
        <div style="flex:1"><label>Moisture (%)</label><input name="moisture" type="number" step="0.1"></div>
        <div style="flex:1"><label>NPK</label><input name="npk"></div>
      </div>
      <div class="row">
        <div style="flex:1"><label>Humidity (%)</label><input name="humidity" type="number" step="0.1"></div>
        <div style="flex:1"><label>Upload image</label><input type="file" accept="image/*" name="image"></div>
      </div>
      <div><button class="btn" type="submit">Create Record</button></div>
    </form>

    <hr>
    <h3>Your submitted records</h3>
    <?php if(!$records): ?>
      <div class="small">No records yet.</div>
    <?php else: ?>
      <table class="table">
        <thead><tr><th>Crop</th><th>Days</th><th>Soil</th><th>Temp</th><th>Moisture</th><th>NPK</th><th>Humidity</th><th>Image</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($records as $r): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['crop_name']) ?></td>
            <td><?php echo htmlspecialchars($r['days_after_plantation']) ?></td>
            <td><?php echo htmlspecialchars($r['soil_type']) ?></td>
            <td><?php echo htmlspecialchars($r['temperature']) ?></td>
            <td><?php echo htmlspecialchars($r['moisture']) ?></td>
            <td><?php echo htmlspecialchars($r['npk']) ?></td>
            <td><?php echo htmlspecialchars($r['humidity']) ?></td>
            <td><?php if($r['image']): ?><a href="uploads/<?php echo htmlspecialchars($r['image']) ?>" target="_blank">View</a><?php endif; ?></td>
            <td><a href="php/edit_record.php?id=<?php echo $r['id'] ?>">Edit</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <hr>
    <h3>Messages from members</h3>
    <div><a href="messages.php" class="btn">Open Chat</a></div>
  </section>
</main>
</body>
</html>
