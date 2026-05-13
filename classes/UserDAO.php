<?php

require_once "BaseDAO.php";
require_once "User.php";

class UserDAO extends BaseDAO {
  // Constructor is inherited from BaseDAO

  public function login($email, $password) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    if ($row && password_verify($password, $row['password'])) {
      return new User($row['id'], $row['name'], $row['email'], $row['password'], $row['role']);
    }

    return null;
  }

  public function create($name, $email, $password, $role = 'user') {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $hashedPassword, $role]);
  }

  public function exists($email) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
  }
}
