<?php

class Database {
  private $host = "127.0.0.1";
  private $db   = "book_library";
  private $user = "root";
  private $pass = "root";
  private $port = "8889";
  private $pdo;

  public function __construct() {
    $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->db;charset=utf8";
    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
      PDO::ATTR_TIMEOUT            => 5, // 5 seconds timeout
    ];

    try {
      $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e) {
      throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  public function getConnection() {
    return $this->pdo;
  }
}
