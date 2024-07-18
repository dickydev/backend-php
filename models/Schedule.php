<?php
require_once __DIR__ . '/../config/database.php';

class Schedule {
    private $conn;
    private $table_name = "schedules"; // Define the table name
    
    // Schedule properties
    public $id;
    public $title;
    public $description;
    public $start_time;
    public $end_time;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implement CRUD methods: create(), read(), read_single(), update(), delete()
    // Example methods are similar to those in User.php
}
?>
