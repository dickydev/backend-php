<?php
class Borrower {
    private $conn;
    private $table_name = "borrowers";

    public $id;
    public $user_id;
    public $lab_id;
    public $start_time;
    public $end_time;
    public $created_at;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array
    }

    public function read_single() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->lab_id = $row['lab_id'];
            $this->start_time = $row['start_time'];
            $this->end_time = $row['end_time'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, lab_id, start_time, end_time, status) VALUES (:user_id, :lab_id, :start_time, :end_time, :status)";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->lab_id = htmlspecialchars(strip_tags($this->lab_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));
        $this->status = htmlspecialchars(strip_tags($this->status));
        

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':lab_id', $this->lab_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function update() {
        // Validate if necessary fields are set
        if (!$this->id) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET user_id = :user_id, lab_id = :lab_id, start_time = :start_time, end_time = :end_time, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->lab_id = htmlspecialchars(strip_tags($this->lab_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':lab_id', $this->lab_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}

?>
