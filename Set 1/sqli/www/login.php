<?php
// dangerously simple SQLi-vulnerable login
$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'ctf';
$pass = getenv('MYSQL_PASSWORD') ?: 'ctfpass';
$dbname = getenv('MYSQL_DATABASE') ?: 'ctfdb';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_errno) {
    echo "DB connect error";
    exit;
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// VULNERABLE: direct interpolation into SQL
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1;";
$result = $mysqli->query($sql);

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Result</title></head>
<body>
<?php
if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    echo "<h2>Welcome, " . htmlspecialchars($row['username']) . "!</h2>";
    // show a "flag" placeholder for CTF
    echo "<p>CTF flag: FLAG{vulnerable-login}</p>";
} else {
    echo "<h2>Login failed</h2>";
    echo "<p><a href=\"index.php\">Try again</a></p>";
}
$mysqli->close();
?>
</body>
</html>
