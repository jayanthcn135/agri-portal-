<?php
require 'config.php';
require_login();
$user = $_SESSION['user'];
if ($user['role'] !== 'farmer') { header("Location: ../dashboard.php"); exit; }

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM records WHERE id = ? AND farmer_id = ?");
$stmt->execute([$id,$user['id']]);
$rec = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$rec) { header("Location: ../dashboard.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crop = $_POST['crop_name'] ?? '';
    $days = $_POST['days'] ?? 0;
    $soil = $_POST['soil_type'] ?? '';
    $temperature = $_POST['temperature'] ?? null;
    $moisture = $_POST['moisture'] ?? null;
    $npk = $_POST['npk'] ?? '';
    $humidity = $_POST['humidity'] ?? null;

    $imgName = $rec['image'];
    if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = __DIR__ . '/../uploads/' . $imgName;
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    }
    $stmt = $pdo->prepare("UPDATE records SET crop_name=?,days_after_plantation=?,soil_type=?,temperature=?,moisture=?,npk=?,humidity=?,image=? WHERE id=?");
    $stmt->execute([$crop,$days,$soil,$temperature,$moisture,$npk,$humidity,$imgName,$id]);
    header("Location: ../dashboard.php");
    exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit Record</title><link rel="stylesheet" href="../css/style.css"></head><body>
<header class="topbar"><div class="logo">AgriTech</div></header><main class="container">
  <div style="max-width:720px;margin:20px auto;background:#fff;padding:16px;border-radius:8px">
    <h3>Edit Record</h3>
    <form method="post" enctype="multipart/form-data">
      <label>Crop Name</label><input name="crop_name" value="<?php echo htmlspecialchars($rec['crop_name']) ?>">
      <div class="row">
        <div style="flex:1"><label>Days</label><input name="days" value="<?php echo htmlspecialchars($rec['days_after_plantation']) ?>"></div>
        <div style="flex:1"><label>Soil Type</label><input name="soil_type" value="<?php echo htmlspecialchars($rec['soil_type']) ?>"></div>
      </div>
      <div class="row">
        <div style="flex:1"><label>Temperature</label><input name="temperature" value="<?php echo htmlspecialchars($rec['temperature']) ?>"></div>
        <div style="flex:1"><label>Moisture</label><input name="moisture" value="<?php echo htmlspecialchars($rec['moisture']) ?>"></div>
      </div>
      <label>NPK</label><input name="npk" value="<?php echo htmlspecialchars($rec['npk']) ?>">
      <label>Humidity</label><input name="humidity" value="<?php echo htmlspecialchars($rec['humidity']) ?>">
      <label>Image</label><input type="file" name="image" accept="image/*">
      <div style="margin-top:10px"><button class="btn" type="submit">Save</button></div>
    </form>
  </div>
</main></body></html>
