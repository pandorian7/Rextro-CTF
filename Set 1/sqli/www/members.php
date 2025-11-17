<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "<p>Unauthorized — admin only.</p>";
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

$sql = "SELECT id, username, role FROM users ORDER BY id ASC;";
$res = $mysqli->query($sql);

$members = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $members[] = $r;
}

$mysqli->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Members — Serendib Systems</title>
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
        <li><a href="/members.php">Members</a></li>
      </ul>
    </aside>

    <main class="main">
      <h1>Members</h1>
      <table class="members">
        <thead><tr><th>ID</th><th>Username</th><th>Role</th></tr></thead>
        <tbody>
          <?php foreach ($members as $m): ?>
            <tr>
              <td><?php echo (int)$m['id']; ?></td>
              <td><?php echo htmlspecialchars($m['username']); ?></td>
              <td><?php echo htmlspecialchars($m['role']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
