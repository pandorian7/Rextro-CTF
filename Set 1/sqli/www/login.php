<?php
session_start();

// Config
$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'svc_3f9b2e1a';
$pass = getenv('MYSQL_PASSWORD') ?: 'X9mD3r7qPz2vK1sL4tYw0bR8';
$dbname = getenv('MYSQL_DATABASE') ?: 'serendibdb';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_errno) {
        die("DB connect error");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // First check existence by username so we can return a specific "No such user." message
    $sql_check = "SELECT * FROM users WHERE username = '$username' LIMIT 1;";
    $res_check = $mysqli->query($sql_check);

    if ($res_check && $res_check->num_rows === 1) {
        // Intentionally vulnerable authentication query (username and password are injectable)
        $sql_auth = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1;";
        $res_auth = $mysqli->query($sql_auth);

        if ($res_auth && $res_auth->num_rows === 1) {
            $row = $res_auth->fetch_assoc();
            // Successful login
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            // Default role to 'user' if DB row doesn't include one to avoid notices
            $_SESSION['role'] = isset($row['role']) && $row['role'] !== '' ? $row['role'] : 'user';
            header('Location: /dashboard.php');
            exit;
        } else {
            $message = 'Password is wrong.';
        }
    } else {
        $message = 'No such user.';
    }
}

$mysqli->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login — Serendib Systems</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-left">Serendib Systems</div>
        <div class="nav-right"><a class="btn" href="/">Home</a></div>
    </nav>

    <main class="auth">
        <div class="auth-card">
            <h2>Sign in</h2>
            <?php if ($message): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="post" action="/login.php">
                <label>Username
                    <input name="username" required>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <div class="auth-actions">
                    <button class="btn btn-primary" type="submit">Login</button>
                    <a class="btn" href="/">Cancel</a>
                </div>
            </form>
            <p class="muted">Internal demo portal. Keep your credentials confidential.</p>
        </div>
    </main>
</body>
</html>
