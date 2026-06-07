<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require_once "library.php";
  require_once "classes/BookDAO.php";

  $siteName  = "Book Library";
  $pageTitle = "Home";

  $error        = "";
  $totalBooks   = 0;
  $displayBooks = [];
  $keyword      = "";

  try {
    $bookDAO    = new BookDAO();
    $totalBooks = $bookDAO->count();
    $displayBooks = $bookDAO->getAll();

    if (isset($_GET["search"]) && $_GET["search"] !== "") {
      $keyword      = $_GET["search"];
      $displayBooks = $bookDAO->search($keyword);
    }
  } catch (Exception $e) {
    $error = "Database Connection Issue: " . $e->getMessage();
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
    <header>
      <h1>📚 <?php echo $siteName; ?></h1>
      <?php echo render_nav(); ?>
    </header>

    <?php if ($error !== ""): ?>
      <div class="card error-msg">
        <p>⚠️ <?php echo htmlspecialchars($error); ?></p>
        <p>Please make sure your database server is running and configured correctly in <code>classes/Database.php</code>.</p>
      </div>
    <?php else: ?>
       <!-- Debug info (hidden in production usually) -->
       <p class="debug-info">Connected to DB: <code>book_library</code> | Books found: <?php echo $totalBooks; ?></p>
    <?php endif; ?>

    <div class="card">
      <p>Welcome to our library! We currently have <strong><?php echo $totalBooks; ?></strong> titles available.</p>
      <form action="index.php" method="GET" class="flex-row">
        <input type="text" name="search" placeholder="Search title or author..."
          value="<?php echo htmlspecialchars($keyword); ?>" class="flex-1 no-margin">
        <button type="submit">Search</button>
        <?php if ($keyword !== ""): ?>
          <a href="index.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
      </form>
      </div>

      <h2>
      <?php if ($keyword !== ""): ?>
        Search results for: "<?php echo htmlspecialchars($keyword); ?>"
      <?php else: ?>
        Featured Collection
      <?php endif; ?>
      </h2>

      <?php if (count($displayBooks) === 0): ?>
      <div class="card"><p>No books found matching your criteria.</p></div>
      <?php else: ?>
      <div class="book-grid">
        <?php foreach ($displayBooks as $book): ?>
          <div class="book-card">
            <?php if ($book->getCoverImage()): ?>
              <img src="uploads/<?php echo htmlspecialchars($book->getCoverImage()); ?>" class="book-cover">
            <?php endif; ?>
            <h3>
              <?php echo getGenreIcon($book->getGenre()); ?> 
              <?php echo htmlspecialchars($book->getDisplayInfo()); ?>
            </h3>
            <div>
              <strong>Author:</strong> <?php echo htmlspecialchars($book->getAuthor()); ?><br>
              <strong>Genre:</strong> <span class="badge"><?php echo htmlspecialchars($book->getGenre()); ?></span><br>
              <strong>Length:</strong> <?php echo getBookLength($book->getPages()); ?> (<?php echo $book->getPages(); ?> pages)<br>
              <strong>Rating:</strong> <span class="stars"><?php echo showStars($book->getRating()); ?></span>
            </div>
            <p class="book-desc">
              <?php echo htmlspecialchars(makePreview($book->getDescription(), 15)); ?>
            </p>
            <div class="mt-10">
               <span class="badge <?php echo $book->getAvailable() ? 'badge-available' : 'badge-unavailable'; ?>">
                ● <?php echo getAvailability($book->getAvailable()); ?>
               </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <footer class="mt-50 center-text gray-text">
      <hr>
      <p>&copy; 2026 <?php echo $siteName; ?> - Coursework Project</p>
      </footer>

  </div>

</body>
</html>
