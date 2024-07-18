<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Schedule.php';

class ScheduleController {
    private $db;
    private $schedule;
    private $table_name = "schedules"; // Define the table name

    public function __construct($db) {
        $this->db = $db;
        $this->schedule = new Schedule($this->db);
    }

    public function getAllSchedules() {
        $result = $this->schedule->read();
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Schedules not found."));
        }
    }
    
    public function getSchedule($id) {
        $this->schedule->id = $id;
        $result = $this->schedule->read_single();

        if ($result) {
            $schedule = array(
                "id" => $this->schedule->id,
                "title" => $this->schedule->title,
                "description" => $this->schedule->description,
                "start_time" => $this->schedule->start_time,
                "end_time" => $this->schedule->end_time,
                "created_at" => $this->schedule->created_at
            );

            header('Content-Type: application/json');
            echo json_encode($schedule);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Schedule not found."));
        }
    }

    public function createSchedule($data) {
        $query = "INSERT INTO " . $this->table_name . " (title, description, start_time, end_time) VALUES (:title, :description, :start_time, :end_time)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSchedule($id, $data) {
        $this->schedule->id = $id;
        $this->schedule->title = $data['title'];
        $this->schedule->description = $data['description'];
        $this->schedule->start_time = $data['start_time'];
        $this->schedule->end_time = $data['end_time'];

        if ($this->schedule->update()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Schedule updated successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to update schedule."));
        }
    }

    public function deleteSchedule($id) {
        $this->schedule->id = $id;

        if ($this->schedule->delete()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "Schedule deleted successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to delete schedule."));
        }
    }
}
?>
