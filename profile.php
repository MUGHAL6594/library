<?php
  require_once "library.php";

  $siteName  = "Book Library";
  $pageTitle = "Profile";

  // Security: If no token exists in session, kick user to login
  if (!isset($_SESSION["token"])) {
    session_write_close();
    header("Location: login.php");
    exit();
  }

  // Verify the JWT token using our secret key
  $userData = verifyJWT($_SESSION["token"], JWT_SECRET);

  // If token is invalid or expired, clear session and kick to login
  if ($userData === null) {
    session_destroy();
    header("Location: login.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $pageTitle; ?> - <?php echo $siteName; ?></title>
  <?php echo get_styles(); ?>
</head>
<body>

  <div class="container">
    <h1>📚 <?php echo $siteName; ?></h1>
    <?php echo render_nav(); ?>

    <div class="card">
      <h2>👤 My Profile</h2>

      <div class="profile-info">
        <p>Name: <strong><?php echo htmlspecialchars($userData["name"]); ?></strong></p>
        <p>Email: <strong><?php echo htmlspecialchars($userData["email"]); ?></strong></p>
        <p>Role: <span class="badge"><?php echo htmlspecialchars($userData["role"]); ?></span></p>
        <p>Token status: <span class="badge badge-available">Valid (Active)</span></p>
      </div>

      <?php if ($userData["role"] === "admin"): ?>
        <div class="mt-20">
          <p>⭐ You have administrative access!</p>
          <a href="manage_books.php" class="btn">Manage Books (CRUD) →</a>
        </div>
      <?php endif; ?>

      <div class="mt-20">
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>

    <footer>
      <hr>
      <p>&copy; 2026 <?php echo $siteName; ?></p>
    </footer>
  </div>

</body>
</html>
