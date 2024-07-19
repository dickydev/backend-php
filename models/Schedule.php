<?php
require_once __DIR__ . '/../config/database.php';

class Schedule {
    private $conn;
    private $table_name = "schedules"; // Define the table name
    
    // Schedule properties
    public $id;
    public $lab_id;
    public $start_time;
    public $end_time;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new schedule
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (lab_id, start_time, end_time) VALUES (:lab_id, :start_time, :end_time)";
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->lab_id = htmlspecialchars(strip_tags($this->lab_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));

        // Bind parameters
        $stmt->bindParam(':lab_id', $this->lab_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all schedules
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single schedule by ID
    public function read_single() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Set properties
            $this->lab_id = $row['lab_id'];
            $this->start_time = $row['start_time'];
            $this->end_time = $row['end_time'];
            $this->created_at = $row['created_at'];
            return true;
        }

        return false;
    }

    // Update a schedule
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET lab_id = :lab_id, start_time = :start_time, end_time = :end_time WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->lab_id = htmlspecialchars(strip_tags($this->lab_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));

        // Bind parameters
        $stmt->bindParam(':lab_id', $this->lab_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a schedule
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
