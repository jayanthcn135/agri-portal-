<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Agri Tech Portal</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
  <div class="logo"><img src="assets/logo.png" alt="logo" id="siteLogo">AgriTech</div>
  <div class="floating-text" id="floating">WELCOME TO AGRI TECH</div>
  <nav class="nav">
    <?php if(isset($_SESSION['user'])): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="profile.php">Profile</a>
      <a href="php/logout.php" class="btn-logout">Logout</a>
    <?php else: ?>
      <a href="login.php" class="btn">Login</a>
      <a href="signup.php" class="btn">Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<main class="container">
  <section class="hero">
    <h1>Smart farming starts here</h1>
    <p>Manage crop records, chat with agriculture members, and get feedback — all in one portal.</p>
    <div class="cta">
      <a href="signup.php?role=farmer" class="btn">Sign up as Farmer</a>
      <a href="signup.php?role=member" class="btn">Sign up as Member</a>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div>Developed by: Your Name — contact@example.com</div>
</footer>

<script src="js/main.js"></script>
</body>
</html>
