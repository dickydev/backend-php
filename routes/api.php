<?php
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../config/database.php';

$request_method = $_SERVER["REQUEST_METHOD"];
$uri = explode('/', $_SERVER['REQUEST_URI']);

$database = new Database();
$db = $database->getConnection();
$userController = new UserController($db);

// Endpoint untuk get user by ID
if ($request_method === 'GET' && isset($uri[5])) {
    $id = intval($uri[5]);
    $userController->getUser($id);
}

// Endpoint untuk get all users
if ($request_method === 'GET' && (isset($uri[4]) && $uri[4] === 'getallusers')) {
    $userController->getAllUsers();
}

// Endpoint untuk create user baru
if ($request_method === 'POST' && (isset($uri[4]) && $uri[4] === 'createuser')) {
    // Pastikan ada data yang dikirim
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!empty($data['username']) && !empty($data['email']) && !empty($data['password']) && !empty($data['role'])) {
        // Panggil method create() di UserController untuk membuat user baru
        if ($userController->create($data)) {
            http_response_code(201); // Created
            echo json_encode(array("message" => "User created successfully."));
        } else {
            http_response_code(500); // Server error
            echo json_encode(array("message" => "Unable to create user."));
        }
    } else {
        http_response_code(400); // Bad request
        echo json_encode(array("message" => "Unable to create user. Incomplete data."));
    }
}

// Endpoint untuk update user (PUT)
if ($request_method === 'PUT' && isset($uri[5]) && $uri[4] === 'updateuser') {
    $id = intval($uri[5]);
    $data = json_decode(file_get_contents("php://input"), true);
    $userController->updateUser($id, $data);
}

// Endpoint untuk delete user (DELETE)
if ($request_method === 'DELETE' && isset($uri[5]) && $uri[4] === 'deleteuser') {
    $id = intval($uri[5]);
    $userController->deleteUser($id);
}


// Endpoint untuk borrower
if ($request_method === 'GET' && isset($uri[5]) && $uri[4] === 'borrowers') {
    $id = intval($uri[5]);
    $borrowerController->getBorrower($id);
}

if ($request_method === 'GET' && (isset($uri[4]) && $uri[4] === 'getallborrowers')) {
    $borrowerController->getAllBorrowers();
}

if ($request_method === 'POST' && (isset($uri[4]) && $uri[4] === 'createborrowers')) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['name']) && !empty($data['email']) && !empty($data['phone'])) {
        if ($borrowerController->create($data)) {
            http_response_code(201); // Created
            echo json_encode(array("message" => "Borrower created successfully."));
        } else {
            http_response_code(500); // Server error
            echo json_encode(array("message" => "Unable to create borrower."));
        }
    } else {
        http_response_code(400); // Bad request
        echo json_encode(array("message" => "Unable to create borrower. Incomplete data."));
    }
}

if ($request_method === 'PUT' && isset($uri[5]) && $uri[4] === 'editborrowers') {
    $id = intval($uri[5]);
    $data = json_decode(file_get_contents("php://input"), true);
    $borrowerController->updateBorrower($id, $data);
}

if ($request_method === 'DELETE' && isset($uri[5]) && $uri[4] === 'deleteborrowers') {
    $id = intval($uri[5]);
    $borrowerController->deleteBorrower($id);
}
?>
