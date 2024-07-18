<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $user;
    private $table_name = "users"; // Define the table name

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($this->db);
    }

    public function getAllUsers() {
        $result = $this->user->read();
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Users not found."));
        }
    }
    
    public function getUser($id) {
        $this->user->id = $id;
        $result = $this->user->read_single();

        if ($result) {
            $user = array(
                "id" => $this->user->id,
                "username" => $this->user->username,
                "email" => $this->user->email,
                "role" => $this->user->role,
                "created_at" => $this->user->created_at
            );

            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "User not found."));
        }
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':role', $data['role']);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function updateUser($id, $data) {
        $this->user->id = $id;
        $this->user->username = $data['username'];
        $this->user->email = $data['email'];
        $this->user->role = $data['role'];

        // Check if password is provided and update it if so
        if (isset($data['password'])) {
            $this->user->password = $data['password'];
        }

        if ($this->user->update()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "User updated successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to update user."));
        }
    }
    
    public function deleteUser($id) {
        $this->user->id = $id;

        if ($this->user->delete()) {
            http_response_code(200); // OK
            echo json_encode(array("message" => "User deleted successfully."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to delete user."));
        }
    }
}
?>
