<?php
require_once __DIR__ . '/../config/database.php';

class Lab {
    private $conn;
    private $table_name = "labs"; // Define the table name
    
    // Lab properties
    public $id;
    public $name;
    public $location;
    public $description;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create a new lab
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, location, description) VALUES (:name, :location, :description)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':description', $this->description);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Read all labs
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    // Read a single lab by id
    public function read_single() {
        $query = "SELECT id, name, location, description FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Bind ID parameter
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Set properties
            $this->name = $row['name'];
            $this->location = $row['location'];
            $this->description = $row['description'];
            return true;
        }

        return false;
    }
    
    // Update a lab
    public function update() {
        // Validate if necessary fields are set
        if (!$this->id) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET name = :name, location = :location, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    // Delete a lab
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
