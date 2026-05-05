<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require_once "library.php";
  require_once "classes/UserDAO.php";

  $siteName  = "Book Library";
  $pageTitle = "Register";

  $error   = "";
  $success = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($name) || empty($email) || empty($password)) {
      $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email format.";
    } else {
      try {
        $userDAO = new UserDAO();
        if ($userDAO->exists($email)) {
          $error = "Email already registered.";
        } else {
          if ($userDAO->create($name, $email, $password)) {
            $success = "Registration successful! You can now login.";
          } else {
            $error = "Something went wrong. Please try again.";
          }
        }
      } catch (Exception $e) {
        $error = "Database Error: " . $e->getMessage();
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
      <h2>Create New Account</h2>

      <?php if ($error !== ""): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <?php if ($success !== ""): ?>
        <p class="success-msg"><?php echo htmlspecialchars($success); ?></p>
        <br>
        <a href="login.php" class="btn">Go to Login Page →</a>
      <?php else: ?>
        <form action="register.php" method="POST">
          <label>Full Name:</label>
          <input type="text" name="name" placeholder="John Doe" required
            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">

          <label>Email Address:</label>
          <input type="email" name="email" placeholder="john@example.com" required
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

          <label>Password:</label>
          <input type="password" name="password" placeholder="Create a strong password" required>

          <button type="submit">Register Now</button>
        </form>
        <p class="center-text">Already have an account? <a href="login.php">Login here</a></p>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
