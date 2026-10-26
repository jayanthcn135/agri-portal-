<?php
require 'php/config.php';
require_login();
$user = $_SESSION['user'];
$stmt = $pdo->prepare("
  SELECT DISTINCT u.id, u.name 
  FROM users u
  JOIN messages m ON (m.from_user = u.id OR m.to_user = u.id)
  WHERE u.id != ? AND (m.from_user = ? OR m.to_user = ?)
");
$stmt->execute([$user['id'],$user['id'],$user['id']]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$with = isset($_GET['with']) ? intval($_GET['with']) : ($contacts[0]['id'] ?? 0);
$conversation = [];
if ($with) {
    $stmt = $pdo->prepare("SELECT m.*, u.name as from_name FROM messages m JOIN users u ON u.id = m.from_user WHERE (m.from_user = ? AND m.to_user = ?) OR (m.from_user = ? AND m.to_user = ?) ORDER BY m.created_at ASC");
    $stmt->execute([$user['id'],$with,$with,$user['id']]);
    $conversation = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Messages</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<header class="topbar"><div class="logo">AgriTech</div><nav class="nav"><a href="dashboard.php">Back</a></nav></header>
<main class="container app">
  <aside class="sidebar">
    <h4>Conversations</h4>
    <?php foreach($contacts as $c): ?>
      <div><a href="?with=<?php echo $c['id'] ?>"><?php echo htmlspecialchars($c['name']) ?></a></div>
    <?php endforeach; ?>
  </aside>
  <section class="main">
    <h3>Chat with <?php echo $with ? htmlspecialchars( ($pdo->query("SELECT name FROM users WHERE id = $with")->fetchColumn()) ) : '...' ?></h3>
    <div style="height:300px;overflow:auto;border:1px solid #eee;padding:12px;background:#fafafa;border-radius:8px">
      <?php foreach($conversation as $m): ?>
        <div style="margin-bottom:8px">
          <strong><?php echo $m['from_user']==$user['id'] ? 'You' : htmlspecialchars($m['from_name']) ?>:</strong>
          <div><?php echo nl2br(htmlspecialchars($m['content'])) ?></div>
          <div class="small"><?php echo $m['created_at'] ?></div>
        </div>
      <?php endforeach; ?>
      <?php if(empty($conversation)): ?><div class="small">No conversation selected or no messages yet.</div><?php endif; ?>
    </div>

    <form method="post" action="php/reply_message.php" style="margin-top:12px;display:flex;gap:8px">
      <input type="hidden" name="to_user" value="<?php echo $with ?>">
      <input name="content" placeholder="Type message" style="flex:1" required>
      <button class="btn" type="submit">Send</button>
    </form>
  </section>
</main>
</body>
</html>
