<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Lab.php';

class LabController {
    private $db;
    private $lab;

    public function __construct($db) {
        $this->db = $db;
        $this->lab = new Lab($this->db);
    }

    public function getAllLabs() {
        $result = $this->lab->read();
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Labs not found."));
        }
    }
    
    public function getLab($id) {
        $this->lab->id = $id;
        if ($this->lab->read_single()) {
            $lab = array(
                "id" => $this->lab->id,
                "name" => $this->lab->name,
                "location" => $this->lab->location,
                "description" => $this->lab->description
            );

            header('Content-Type: application/json');
            echo json_encode($lab);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Lab not found."));
        }
    }

    public function createLab($data) {
        $this->lab->name = $data['name'];
        $this->lab->location = $data['location'];
        $this->lab->description = $data['description'];

        if ($this->lab->create()) {
            http_response_code(201); // Created
            echo json_encode(array("message" => "Lab created successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to create lab."));
        }
    }

    public function updateLab($id, $data) {
        $this->lab->id = $id;
        $this->lab->name = $data['name'];
        $this->lab->location = $data['location'];
        $this->lab->description = $data['description'];

        if ($this->lab->update()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Lab updated successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to update lab."));
        }
    }

    public function deleteLab($id) {
        $this->lab->id = $id;

        if ($this->lab->delete()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Lab deleted successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to delete lab."));
        }
    }
}
?>
