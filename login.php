<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require_once "library.php";
  require_once "classes/UserDAO.php";

  $siteName  = "Book Library";
  $pageTitle = "Login";

  $error   = "";
  $success = "";
  $token   = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
      $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Please enter a valid email address.";
    } else {
      try {
        $userDAO   = new UserDAO();
        $foundUser = $userDAO->login($email, $password);

        if ($foundUser === null) {
          $error = "Wrong email or password.";
        } else {
          // PROFESSIONAL: Logic is moved to library.php
          $token = authenticateUser($foundUser);
          session_write_close();
          header("Location: profile.php");
          exit();
        }
      } catch (Exception $e) {
        $error = "Database Connection Error: " . $e->getMessage();
      }
    }
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
      <h2>Login</h2>

      <?php if ($error !== ""): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <form action="login.php" method="POST">

          <label>Email Address:</label>
          <input type="email" name="email" placeholder="you@example.com" required
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

          <label>Password:</label>
          <input type="password" name="password" placeholder="Enter your password" required>

          <button type="submit">Sign In</button>
        </form>

        <p class="center-text">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>

</body>
</html>
