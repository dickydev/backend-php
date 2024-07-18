<?php
class Borrower {
    private $conn;
    private $table_name = "borrowers"; // Define the table name
    
    // Borrower properties
    public $id;
    public $name;
    public $email;
    public $phone;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Borrower
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, phone) VALUES (:name, :email, :phone)";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Read all Borrowers
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single Borrower
    public function read_single() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update Borrower
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email, phone = :phone WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Delete Borrower
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
