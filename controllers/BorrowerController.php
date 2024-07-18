<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Borrower.php';

class BorrowerController {
    private $db;
    private $borrower;
    private $table_name = "borrowers"; // Define the table name

    public function __construct($db) {
        $this->db = $db;
        $this->borrower = new Borrower($this->db);
    }

    public function getAllBorrowers() {
        $result = $this->borrower->read();
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Borrowers not found."));
        }
    }
    
    public function getBorrower($id) {
        $this->borrower->id = $id;
        $result = $this->borrower->read_single();

        if ($result) {
            $borrower = array(
                "id" => $this->borrower->id,
                "name" => $this->borrower->name,
                "email" => $this->borrower->email,
                "phone" => $this->borrower->phone,
                "created_at" => $this->borrower->created_at
            );

            header('Content-Type: application/json');
            echo json_encode($borrower);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Borrower not found."));
        }
    }

    public function createBorrower($data) {
        $query = "INSERT INTO " . $this->table_name . " (name, email, phone) VALUES (:name, :email, :phone)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateBorrower($id, $data) {
        $this->borrower->id = $id;
        $this->borrower->name = $data['name'];
        $this->borrower->email = $data['email'];
        $this->borrower->phone = $data['phone'];

        if ($this->borrower->update()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Borrower updated successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to update borrower."));
        }
    }

    public function deleteBorrower($id) {
        $this->borrower->id = $id;

        if ($this->borrower->delete()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Borrower deleted successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to delete borrower."));
        }
    }
}
?>
