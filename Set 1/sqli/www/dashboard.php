<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'svc_3f9b2e1a';
$pass = getenv('MYSQL_PASSWORD') ?: 'X9mD3r7qPz2vK1sL4tYw0bR8';
$dbname = getenv('MYSQL_DATABASE') ?: 'serendibdb';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_errno) {
    die('DB connect error');
}

$uid = (int)$_SESSION['user_id'];
$sql = "SELECT id, username, role, bio FROM users WHERE id = $uid LIMIT 1;";
$res = $mysqli->query($sql);
$user = $res ? $res->fetch_assoc() : null;

$mysqli->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — Serendib Systems</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="nav">
    <div class="nav-left">Serendib Systems</div>
    <div class="nav-right">Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> &nbsp; <a class="btn" href="/logout.php">Logout</a></div>
  </nav>

  <div class="app">
    <aside class="sidebar">
      <ul>
        <li><a href="/profile.php">Profile</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li><a href="/members.php">Members</a></li>
        <?php endif; ?>
      </ul>
    </aside>

    <main class="main">
      <h1>Welcome back, <?php echo htmlspecialchars($user ? $user['username'] : $_SESSION['username']); ?>!</h1>
      <p class="lead">Role: <?php echo htmlspecialchars($user ? $user['role'] : $_SESSION['role']); ?></p>
      <section class="panel">
        <h3>Your bio</h3>
        <p><?php echo nl2br(htmlspecialchars($user ? $user['bio'] : '')); ?></p>
      </section>
    </main>
  </div>
</body>
</html>
