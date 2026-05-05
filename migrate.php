<?php
require_once "classes/Database.php";

try {
    $db = new Database();
    $conn = $db->getConnection();

    // 1. Handle file_path -> cover_image migration
    // Check if cover_image column exists
    $stmt = $conn->query("SHOW COLUMNS FROM books LIKE 'cover_image'");
    $coverExists = $stmt->fetch();

    if (!$coverExists) {
        // Maybe file_path exists?
        $stmt = $conn->query("SHOW COLUMNS FROM books LIKE 'file_path'");
        $filePathExists = $stmt->fetch();

        if ($filePathExists) {
            $conn->exec("ALTER TABLE books CHANGE COLUMN file_path cover_image VARCHAR(255) DEFAULT NULL");
            echo "Renamed 'file_path' to 'cover_image'.\n";
        } else {
            $conn->exec("ALTER TABLE books ADD COLUMN cover_image VARCHAR(255) DEFAULT NULL");
            echo "Added 'cover_image' column.\n";
        }
    } else {
        echo "Column 'cover_image' already exists.\n";
    }

    // 2. Fix the "Pakistan" book
    // First, delete any broken "Pakistan" entries if they exist (due to the typo)
    $conn->exec("DELETE FROM books WHERE title = 'pakistan' OR title = 'Pakistan'");
    
    // Add it correctly
    $stmt = $conn->prepare("INSERT INTO books (title, author, genre, pages, rating, description, available) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute(['Pakistan', 'Nawaj', 'Politics', 10, 5, 'A book about politics.', 1]);
    echo "Re-inserted 'Pakistan' book correctly.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
