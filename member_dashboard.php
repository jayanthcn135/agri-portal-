<?php
require 'php/config.php';
$user = $_SESSION['user'];
$stmt = $pdo->prepare("
  SELECT r.*, u.name as farmer_name, fd.village, fd.taluk
  FROM records r
  JOIN users u ON r.farmer_id = u.id
  LEFT JOIN farmer_details fd ON fd.user_id = u.id
  ORDER BY r.created_at DESC
");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Member Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<script src="js/main.js"></script>
</head>
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
    <h3>Member: <?php echo htmlspecialchars($user['name']) ?></h3>
    <div style="margin-top:12px">
      <a href="messages.php" class="btn-ghost">Messages</a>
    </div>
  </aside>

  <section class="main">
    <h2>All Farmer Records</h2>
    <div class="searchbar">
      <input id="filterCrop" class="filter-input" placeholder="Search crop name">
      <input id="filterVillage" class="filter-input" placeholder="Village">
      <input id="filterTaluk" class="filter-input" placeholder="Taluk">
    </div>
    <table class="table" id="recordsTable">
      <thead><tr><th>Farmer</th><th>Crop</th><th>Village</th><th>Taluk</th><th>Days</th><th>Soil</th><th>Temp</th><th>Moisture</th><th>NPK</th><th>Humidity</th><th>Image</th><th>Feedback</th></tr></thead>
      <tbody>
        <?php foreach($records as $r): ?>
          <tr data-crop="<?php echo htmlspecialchars($r['crop_name']) ?>" data-village="<?php echo htmlspecialchars($r['village'] ?? '') ?>" data-taluk="<?php echo htmlspecialchars($r['taluk'] ?? '') ?>">
            <td><?php echo htmlspecialchars($r['farmer_name']) ?></td>
            <td><?php echo htmlspecialchars($r['crop_name']) ?></td>
            <td><?php echo htmlspecialchars($r['village'] ?? '-') ?></td>
            <td><?php echo htmlspecialchars($r['taluk'] ?? '-') ?></td>
            <td><?php echo htmlspecialchars($r['days_after_plantation']) ?></td>
            <td><?php echo htmlspecialchars($r['soil_type']) ?></td>
            <td><?php echo htmlspecialchars($r['temperature']) ?></td>
            <td><?php echo htmlspecialchars($r['moisture']) ?></td>
            <td><?php echo htmlspecialchars($r['npk']) ?></td>
            <td><?php echo htmlspecialchars($r['humidity']) ?></td>
            <td><?php if($r['image']): ?><a href="uploads/<?php echo htmlspecialchars($r['image']) ?>" target="_blank">View</a><?php endif; ?></td>
            <td>
              <form method="post" action="php/reply_message.php" style="display:flex;gap:6px">
                <input type="hidden" name="to_user" value="<?php echo htmlspecialchars($r['farmer_id']) ?>">
                <input name="content" placeholder="Send feedback" required>
                <button class="btn" type="submit">Send</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>
</body>
</html>
