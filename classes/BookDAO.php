<?php

require_once "BaseDAO.php";
require_once "Book.php";

class BookDAO extends BaseDAO {
  // Constructor is inherited from BaseDAO

  public function count() {
    $stmt = $this->db->query("SELECT COUNT(*) FROM books");
    return $stmt->fetchColumn();
  }

  public function getAll() {
    $stmt  = $this->db->query("SELECT * FROM books");
    $books = [];
    while ($row = $stmt->fetch()) {
      $books[] = new Book(
        $row['id'], $row['title'], $row['author'], $row['genre'],
        $row['pages'], $row['rating'], $row['description'], $row['available'],
        $row['cover_image'] ?? null
      );
    }
    return $books;
  }

  public function search($keyword) {
    // Split keywords by space to handle multiple terms like "Dark 1984"
    $terms = explode(" ", $keyword);
    $query = "SELECT * FROM books WHERE ";
    $params = [];
    $conditions = [];

    foreach ($terms as $term) {
        if (trim($term) === "") continue;
        $conditions[] = "(title LIKE ? OR author LIKE ? OR genre LIKE ? OR description LIKE ?)";
        $params[] = "%$term%";
        $params[] = "%$term%";
        $params[] = "%$term%";
        $params[] = "%$term%";
    }

    if (empty($conditions)) {
        return $this->getAll();
    }

    $query .= implode(" AND ", $conditions);
    
    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    
    $books = [];
    while ($row = $stmt->fetch()) {
      $books[] = new Book(
        $row['id'], $row['title'], $row['author'], $row['genre'],
        $row['pages'], $row['rating'], $row['description'], $row['available'],
        $row['cover_image'] ?? null
      );
    }
    return $books;
  }

  public function create($title, $author, $genre, $pages, $rating, $description, $available = true, $coverImage = null) {
    $stmt = $this->db->prepare("INSERT INTO books (title, author, genre, pages, rating, description, available, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$title, $author, $genre, $pages, $rating, $description, $available, $coverImage]);
  }

  public function update($id, $title, $author, $genre, $pages, $rating, $description, $available) {
    $stmt = $this->db->prepare("UPDATE books SET title = ?, author = ?, genre = ?, pages = ?, rating = ?, description = ?, available = ? WHERE id = ?");
    return $stmt->execute([$title, $author, $genre, $pages, $rating, $description, $available, $id]);
  }

  public function delete($id) {
    $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
    return $stmt->execute([$id]);
  }
}
