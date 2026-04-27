<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db.php';
CONST MIO_ERRORE = "Invalid request method";

$database = new Database();
$conn = $database->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        if (isset($_GET['id'])) {
            getSingleUser($conn, $_GET['id']);
        } else {
            getAllUsers($conn);
        }
        break;

    case 'POST':
        insertUser($conn);
        break;

    case 'PUT':
        updateUser($conn);
        break;

    case 'DELETE':
        deleteUser($conn);
        break;

    case 'OPTIONS':
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "OK"
        ]);
        break;

    default:
        echo json_encode([
            "status" => false,
            "message" => MIO_ERRORE
        ]);
        break;
}

function getAllUsers($conn) {
    $stmt = $conn->prepare("SELECT id, fullname, email, created_at FROM users");
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "data" => $users
    ]);
}

function getSingleUser($conn, $id) {
    $stmt = $conn->prepare("SELECT id, fullname, email, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            "status" => true,
            "data" => $user
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "User not found"
        ]);
    }
}


function insertUser($conn) {
    //$data = json_decode(file_get_contents("php://input"), true);

    $localFullname = trim($_POST['fullname']);
    $localEmail = trim($_POST['email']);
    $localPassword = trim($_POST['password']);

    if (
        empty($localFullname) ||
        empty($localEmail) ||
        empty($localPassword)
    ) {
        echo json_encode([
            "status" => false,
            "message" => "All fields required"
        ]);
        return;
    }

    $password = password_hash($localPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users(fullname,email,password) VALUES(?,?,?)"
    );

    $success = $stmt->execute([
        $localFullname,
        $localEmail,
        $password
    ]);

    echo json_encode([
        "status" => $success,
        "message" => $success ? "User created" : "Insert failed"
    ]);
}

function updateUser($conn) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        empty($data['id']) ||
        empty($data['fullname']) ||
        empty($data['email'])
    ) {
        echo json_encode([
            "status" => false,
            "message" => "ID, fullname and email required"
        ]);
        return;
    }

    $stmt = $conn->prepare(
        "UPDATE users SET fullname = ?, email = ? WHERE id = ?"
    );

    $success = $stmt->execute([
        $data['fullname'],
        $data['email'],
        $data['id']
    ]);

    echo json_encode([
        "status" => $success,
        "message" => $success ? "User updated" : "Update failed"
    ]);
}

function deleteUser($conn) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id'])) {
        echo json_encode([
            "status" => false,
            "message" => "User ID required"
        ]);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $success = $stmt->execute([$data['id']]);

    echo json_encode([
        "status" => $success,
        "message" => $success ? "User deleted" : "Delete failed"
    ]);
}
?>