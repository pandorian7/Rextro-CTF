<?php
// simple landing + form
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>CTF SQLi — Login</title>
</head>
<body>
  <h1>Login</h1>
  <form action="login.php" method="post">
    <label>Username: <input name="username" /></label><br/>
    <label>Password: <input type="password" name="password" /></label><br/>
    <button type="submit">Login</button>
  </form>
  <p>For CTF use only — intentionally vulnerable.</p>
</body>
</html>
