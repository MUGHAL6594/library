<?php
require_once "Database.php";

/**
 * HW 7 Requirement: Inheritance
 * This is the parent class for all Data Access Objects.
 */
abstract class BaseDAO {
    protected $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }
}
