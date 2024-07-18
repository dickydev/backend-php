<?php
require_once __DIR__ . '/../config/database.php';

class Lab {
    private $conn;
    private $table_name = "labs"; // Define the table name
    
    // Lab properties
    public $id;
    public $name;
    public $location;
    public $capacity;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implement CRUD methods: create(), read(), read_single(), update(), delete()
    // Example methods are similar to those in User.php
}
?>
