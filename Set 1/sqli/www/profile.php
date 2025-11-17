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

$viewId = (int)$_SESSION['user_id'];

// Only allow viewing the logged in user's profile. The id is taken from session.
$sql = "SELECT id, username, role, bio FROM users WHERE id = $viewId LIMIT 1;";
$res = $mysqli->query($sql);
$target = $res ? $res->fetch_assoc() : null;

$mysqli->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Profile — Serendib Systems</title>
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
      <?php if (!$target): ?>
        <h2>User not found</h2>
      <?php else: ?>
        <h1>Profile: <?php echo htmlspecialchars($target['username']); ?></h1>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($target['role']); ?></p>
        <section class="panel">
          <h3>Bio</h3>
          <p><?php echo nl2br(htmlspecialchars($target['bio'])); ?></p>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
