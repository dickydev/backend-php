<?php
    require_once __DIR__ . '/../controllers/UserController.php';
    require_once __DIR__ . '/../controllers/BorrowerController.php';
    require_once __DIR__ . '/../controllers/LabController.php';
    require_once __DIR__ . '/../controllers/ScheduleController.php';
    require_once __DIR__ . '/../config/database.php';

    // Menambahkan header CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Jika request method OPTIONS, langsung return 200
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    
    $request_method = $_SERVER["REQUEST_METHOD"];
    $uri = explode('/', $_SERVER['REQUEST_URI']);

    $database = new Database();
    $db = $database->getConnection();
    $userController = new UserController($db);
    $borrowerController = new BorrowerController($db); // Inisialisasi BorrowerController
    $labController = new LabController($db);
    $scheduleController = new ScheduleController($db);

    // Endpoint untuk user
    if ($request_method === 'GET' && isset($uri[5]) && $uri[4] === 'users') {
        $id = intval($uri[5]);
        $userController->getUser($id);
    }

    if ($request_method === 'GET' && (isset($uri[4]) && $uri[4] === 'getallusers')) {
        $userController->getAllUsers();
    }

    if ($request_method === 'POST' && (isset($uri[4]) && $uri[4] === 'createuser')) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['username']) && !empty($data['email']) && !empty($data['password']) && !empty($data['role'])) {
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

    if ($request_method === 'PUT' && isset($uri[5]) && $uri[4] === 'updateuser') {
        $id = intval($uri[5]);
        $data = json_decode(file_get_contents("php://input"), true);
        $userController->updateUser($id, $data);
    }

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

    if ($request_method === 'POST' && (isset($uri[4]) && $uri[4] === 'createborrower')) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['user_id']) && !empty($data['lab_id']) && !empty($data['start_time']) && !empty($data['end_time']) && !empty($data['status']) ) {
            if ($borrowerController->createBorrower($data)) {
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

    if ($request_method === 'PUT' && isset($uri[5]) && $uri[4] === 'updateborrower') {
        $id = intval($uri[5]);
        $data = json_decode(file_get_contents("php://input"), true);
        $borrowerController->updateBorrower($id, $data);
    }

    if ($request_method === 'DELETE' && isset($uri[5]) && $uri[4] === 'deleteborrower') {
        $id = intval($uri[5]);
        $borrowerController->deleteBorrower($id);
    }


    // Endpoint untuk lab
    if ($request_method === 'GET' && isset($uri[5]) && $uri[4] === 'labs') {
        $id = intval($uri[5]);
        $labController->getLab($id);
    }

    if ($request_method === 'GET' && (isset($uri[4]) && $uri[4] === 'getalllabs')) {
        $labController->getAllLabs();
    }

    if ($request_method === 'POST' && (isset($uri[4]) && $uri[4] === 'createlab')) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['name']) && !empty($data['location']) && !empty($data['description'])) {
            if ($labController->createLab($data)) {
                http_response_code(201); // Created
                echo json_encode(array("message" => "Lab created successfully."));
            } else {
                http_response_code(500); // Server error
                echo json_encode(array("message" => "Unable to create lab."));
            }
        } else {
            http_response_code(400); // Bad request
            echo json_encode(array("message" => "Unable to create lab. Incomplete data."));
        }
    }

    if ($request_method === 'PUT' && isset($uri[5]) && $uri[4] === 'updatelab') {
        $id = intval($uri[5]);
        $data = json_decode(file_get_contents("php://input"), true);
        $labController->updateLab($id, $data);
    }

    if ($request_method === 'DELETE' && isset($uri[5]) && $uri[4] === 'deletelab') {
        $id = intval($uri[5]);
        $labController->deleteLab($id);
    }

      // Endpoint untuk schedule
      if ($request_method === 'GET' && isset($uri[5]) && $uri[4] === 'schedules') {
        $id = intval($uri[5]);
        $scheduleController->getSchedule($id);
    }

    if ($request_method === 'GET' && (isset($uri[4]) && $uri[4] === 'getallschedules')) {
        $scheduleController->getAllSchedules();
    }

    if ($request_method === 'POST' && (isset($uri[4]) && $uri[4] === 'createschedule')) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['lab_id']) && !empty($data['start_time']) && !empty($data['end_time'])) {
            if ($scheduleController->createSchedule($data)) {
                http_response_code(201); // Created
                echo json_encode(array("message" => "Schedule created successfully."));
            } else {
                http_response_code(500); // Server error
                echo json_encode(array("message" => "Unable to create schedule."));
            }
        } else {
            http_response_code(400); // Bad request
            echo json_encode(array("message" => "Unable to create schedule. Incomplete data."));
        }
    }

    if ($request_method === 'PUT' && isset($uri[5]) && $uri[4] === 'updateschedule') {
        $id = intval($uri[5]);
        $data = json_decode(file_get_contents("php://input"), true);
        $scheduleController->updateSchedule($id, $data);
    }

    if ($request_method === 'DELETE' && isset($uri[5]) && $uri[4] === 'deleteschedule') {
        $id = intval($uri[5]);
        $scheduleController->deleteSchedule($id);
    }

?>
