<?php
  require_once "library.php";
  require_once "classes/Uploader.php";

  $siteName  = "Book Library";
  $pageTitle = "Upload Book Cover";

  if (!isset($_SESSION["token"])) {
    header("Location: login.php");
    exit();
  }

  $error   = "";
  $success = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_FILES["cover"]) || $_FILES["cover"]["error"] === UPLOAD_ERR_NO_FILE) {
      $error = "Please select a file to upload.";
    } else {
      $uploader = new Uploader();
      $result   = $uploader->upload($_FILES["cover"]);

      if (isset($result["error"])) {
        $error = $result["error"];
      } else {
        $success = "File uploaded successfully! Saved as: " . $result["file"];
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
      <h2>Upload Book Cover</h2>
      <p>Accepted formats: <strong>JPG, PNG, GIF</strong>. (Max 2MB)</p>

      <?php if ($error !== ""): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <?php if ($success !== ""): ?>
        <p class="success-msg"><?php echo htmlspecialchars($success); ?></p>
        <div class="upload-success-container">
          <?php
            // Extract the filename from the success message
            $newFileName = substr($success, strpos($success, "cover_"));
            echo '<img src="uploads/' . htmlspecialchars($newFileName) . '" class="upload-preview"><br>';
          ?>
        </div>
      <?php endif; ?>

      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label>Select Image File:</label>
        <input type="file" name="cover" required>
        <button type="submit" class="mt-10">🚀 Start Upload</button>
      </form>
    </div>
  </div>

</body>
</html>
