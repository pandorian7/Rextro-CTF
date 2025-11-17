<?php
// Home / landing page for the user management portal
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Serendib Systems — User Portal</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="nav">
    <div class="nav-left">Serendib Systems</div>
    <div class="nav-right"><a class="btn" href="/login.php">Login</a></div>
  </nav>

  <header class="hero">
    <div class="hero-inner">
      <h1>Serendib Systems — User Management</h1>
      <p class="lead">A simple user management portal for internal use. Authenticate to view your profile and assigned tasks.</p>
      <p><a class="btn btn-primary" href="/login.php">Sign in</a></p>
    </div>
  </header>

  <main class="container">
    <section class="cards">
      <article class="card">
        <h3>Accounts & Roles</h3>
        <p>Manage user information and roles. Administrators can access the members list for administrative tasks.</p>
      </article>
      <article class="card">
        <h3>Secure by Design</h3>
        <p>Please keep credentials private and follow company security policies when accessing this portal.</p>
      </article>
    </section>
  </main>

  <footer class="footer">Serendib Systems — Internal Portal.</footer>
</body>
</html>
