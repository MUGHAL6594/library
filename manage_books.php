<?php
  require_once "library.php";
  require_once "classes/BookDAO.php";

  $siteName  = "Book Library";
  $pageTitle = "Manage Books";

  if (!isset($_SESSION["token"])) {
    header("Location: login.php");
    exit();
  }

  $userData = verifyJWT($_SESSION["token"], JWT_SECRET);

  if ($userData === null || $userData["role"] !== "admin") {
    die("Access denied. Admin access required.");
  }

  $bookDAO = new BookDAO();
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
      <h2>Manage Book Collection</h2>
      
      <?php
      try {
        if (isset($_GET["delete"])) {
          if ($bookDAO->delete($_GET["delete"])) {
            echo "<p class='success-msg'>Book deleted successfully.</p>";
          }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
          $title       = trim($_POST["title"]);
          $author      = trim($_POST["author"]);
          $genre       = trim($_POST["genre"]);
          $pages       = $_POST["pages"] === "" ? null : (int)$_POST["pages"];
          $rating      = $_POST["rating"] === "" ? null : (int)$_POST["rating"];
          $description = trim($_POST["description"]);
          $available   = isset($_POST["available"]) ? 1 : 0;
          
          $coverImage = null;
          // Handle Book Cover Upload
          if (isset($_FILES["cover_image"]) && $_FILES["cover_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
            require_once "classes/Uploader.php";
            $uploader = new Uploader();
            $uploadResult = $uploader->upload($_FILES["cover_image"]);
            if (isset($uploadResult["success"])) {
              $coverImage = $uploadResult["file"];
            } else {
              echo "<p class='error-msg'>Upload Error: " . htmlspecialchars($uploadResult["error"]) . "</p>";
            }
          }

          if ($bookDAO->create($title, $author, $genre, $pages, $rating, $description, $available, $coverImage)) {
            echo "<p class='success-msg'>New book added to library.</p>";
          }
        }
      } catch (Exception $e) {
        echo "<p class='error-msg'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
      }

      $books = $bookDAO->getAll();
      ?>

      <h3>Add New Book</h3>
      <form action="manage_books.php" method="POST" enctype="multipart/form-data" class="grid-2">
        <div>
          <label>Title:</label>
          <input type="text" name="title" placeholder="e.g. The Great Gatsby" required>
        </div>
        <div>
          <label>Author:</label>
          <input type="text" name="author" placeholder="e.g. F. Scott Fitzgerald" required>
        </div>
        <div>
          <label>Genre:</label>
          <input type="text" name="genre" placeholder="e.g. Classic">
        </div>
        <div>
          <label>Cover Image:</label>
          <input type="file" name="cover_image" accept="image/*">
        </div>
        <div>
          <label>Pages:</label>
          <input type="number" name="pages" placeholder="Total pages">
        </div>
        <div>
          <label>Rating (1-5):</label>
          <input type="number" name="rating" min="1" max="5">
        </div>
        <div class="span-2">
          <label>Description:</label>
          <textarea name="description" rows="3" placeholder="Brief summary..."></textarea>
        </div>
        <div class="span-2">
          <label><input type="checkbox" name="available" checked> Book is currently available for checkout</label>
        </div>
        <button type="submit" name="add" class="span-2">➕ Add to Collection</button>
      </form>
    </div>

    <div class="card">
      <h3>Current Inventory</h3>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Status</th>
              <th class="center-text">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($books as $book): ?>
              <tr>
                <td><?php echo htmlspecialchars($book->getTitle()); ?></td>
                <td><?php echo htmlspecialchars($book->getAuthor()); ?></td>
                <td>
                   <span class="badge <?php echo $book->getAvailable() ? 'badge-available' : 'badge-unavailable'; ?>">
                    <?php echo $book->getAvailable() ? 'Available' : 'Out'; ?>
                   </span>
                </td>
                <td class="center-text">
                  <a href="manage_books.php?delete=<?php echo $book->getId(); ?>" 
                     class="btn btn-danger"
                     onclick="return confirm('Delete this book permanently?')">🗑️ Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>
</html>
