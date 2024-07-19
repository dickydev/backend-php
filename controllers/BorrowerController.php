<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Borrower.php';

class BorrowerController {
    private $db;
    private $borrower;
    private $table_name = "borrowers";

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
            http_response_code(404);
            echo json_encode(array("message" => "Borrowers not found."));
        }
    }

    public function getBorrower($id) {
        $this->borrower->id = $id;
        $result = $this->borrower->read_single();

        if ($result) {
            $borrower = array(
                "id" => $this->borrower->id,
                "user_id" => $this->borrower->user_id,
                "lab_id" => $this->borrower->lab_id,
                "start_time" => $this->borrower->start_time,
                "end_time" => $this->borrower->end_time,
                "status" => $this->borrower->status,
                "created_at" => $this->borrower->created_at
            );

            header('Content-Type: application/json');
            echo json_encode($borrower);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Borrower not found."));
        }
    }

    public function createBorrower($data) {
        $this->borrower->user_id = $data['user_id'];
        $this->borrower->lab_id = $data['lab_id'];
        $this->borrower->start_time = $data['start_time'];
        $this->borrower->end_time = $data['end_time'];
        $this->borrower->status = $data['status'];

        if ($this->borrower->create()) {
            return true;
        }
        
        return false;
    }

    public function updateBorrower($id, $data) {
        $this->borrower->id = $id;
        $this->borrower->user_id = $data['user_id'];
        $this->borrower->lab_id = $data['lab_id'];
        $this->borrower->start_time = $data['start_time'];
        $this->borrower->end_time = $data['end_time'];
        $this->borrower->status = $data['status'];

        if ($this->borrower->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Borrower updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update borrower."));
        }
    }

    public function deleteBorrower($id) {
        $this->borrower->id = $id;

        if ($this->borrower->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Borrower deleted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete borrower."));
        }
    }
}
?>
