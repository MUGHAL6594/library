<?php

class User {
  private $id;
  private $name;
  private $email;
  private $password;
  private $role;

  public function __construct($id, $name, $email, $password, $role) {
    $this->id       = $id;
    $this->name     = $name;
    $this->email    = $email;
    $this->password = $password;
    $this->role     = $role;
  }

  public function getId() { return $this->id; }
  public function getName() { return $this->name; }
  public function getEmail() { return $this->email; }
  public function getRole() { return $this->role; }
  public function checkPassword($password) { return password_verify($password, $this->password); }

  // Additional Method: Concatenation for greeting
  public function getWelcomeMessage() {
    return "Welcome back, " . $this->name . "! You are logged in as " . $this->role . ".";
  }
}
