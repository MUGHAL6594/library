<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function 1: Count books
function countBooks($books) {
  return count($books);
}

// Function 2: Updated if-elseif-else for better length categorization
function getBookLength($pages) {
  if ($pages <= 0) {
    return "Invalid length";
  } elseif ($pages < 150) {
    return "Short (Quick Read)";
  } elseif ($pages < 350) {
    return "Medium (Standard)";
  } elseif ($pages < 550) {
    return "Long (Full Novel)";
  } else {
    return "Epic (Tome)";
  }
}

// Function 3: Search books
function searchBooks($books, $keyword) {
  $results = [];
  foreach ($books as $book) {
    if (stripos($book["title"], $keyword) !== false) {
      $results[] = $book;
    }
  }
  return $results;
}

// Function 4: Get books by author
function getBooksByAuthor($books, $author) {
  $results = [];
  foreach ($books as $book) {
    if ($book["author"] === $author) {
      $results[] = $book;
    }
  }
  return $results;
}

// Function 5: Availability
function getAvailability($available) {
  if ($available === true) {
    return "Available";
  } else {
    return "Not Available";
  }
}

// Function 6: switch genre icon
function getGenreIcon($genre) {
  switch ($genre) {
    case "Classic":
      return "Book";
    case "Dystopia":
      return "Dark";
    case "Fantasy":
      return "Magic";
    case "Science":
      return "Science";
    default:
      return "Book";
  }
}

// Function 7: for loop stars
function showStars($rating) {
  $stars = "";
  for ($i = 1; $i <= 5; $i++) {
    if ($i <= $rating) {
      $stars .= "*";
    } else {
      $stars .= "-";
    }
  }
  return $stars;
}

// Function 8: while loop preview
function makePreview($text, $maxWords) {
  $words   = explode(" ", $text);
  $preview = "";
  $i       = 0;
  while ($i < $maxWords && $i < count($words)) {
    $preview .= $words[$i] . " ";
    $i++;
  }
  return trim($preview) . "...";
}

// JWT: Create token
function createJWT($payload, $secret) {
  $header = base64_encode(json_encode(array(
    "alg" => "HS256",
    "typ" => "JWT"
  )));

  $payload = base64_encode(json_encode($payload));

  $signature = base64_encode(hash_hmac(
    "sha256",
    $header . "." . $payload,
    $secret,
    true
  ));

  return $header . "." . $payload . "." . $signature;
}

// JWT: Verify token
function verifyJWT($token, $secret) {
  $parts = explode(".", $token);

  if (count($parts) !== 3) {
    return null;
  }

  $header    = $parts[0];
  $payload   = $parts[1];
  $signature = $parts[2];

  $validSignature = base64_encode(hash_hmac(
    "sha256",
    $header . "." . $payload,
    $secret,
    true
  ));

  if ($signature !== $validSignature) {
    return null;
  }

  $decoded = json_decode(base64_decode($payload), true);

  if (isset($decoded["exp"]) && $decoded["exp"] < time()) {
    return null;
  }

  return $decoded;
}

// SECURITY: Centralized Secret Key
define('JWT_SECRET', 'my_book_library_secret_123');

// Function 11: Dynamic Navigation
function render_nav() {
  $nav = '<nav><a href="index.php">🏠 Home</a>';

  if (isset($_SESSION["token"])) {
    $nav .= '<a href="profile.php">👤 Profile</a>';
    
    // Check if user is admin to show Manage Books link
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
      $nav .= '<a href="manage_books.php">⚙️ Manage Books</a>';
    }

    $nav .= '<a href="logout.php" class="logout">🚪 Logout</a>';
  } else {
    $nav .= '<a href="login.php">🔑 Login</a>';
    $nav .= '<a href="register.php">📝 Register</a>';
  }

  $nav .= '</nav>';

  return $nav;
}

// Function to include CSS
function get_styles() {
  return '<link rel="stylesheet" href="style.css">';
}

// Function 12: Authenticate User (Centralized Logic)
function authenticateUser($user) {
  // 1. Prepare Data
  $payload = [
    "name"  => $user->getName(),
    "email" => $user->getEmail(),
    "role"  => $user->getRole(),
    "iat"   => time(),
    "exp"   => time() + (60 * 60), // Expires in 1 hour
  ];

  // 2. Create Token
  $token = createJWT($payload, JWT_SECRET);

  // 3. Set Session
  $_SESSION["token"] = $token;
  $_SESSION["user"]  = $user->getName();
  $_SESSION["role"]  = $user->getRole();

  return $token;
}