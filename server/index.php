<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// CORS

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');

// Обрабатываем preflight запросы (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}



require_once '../server/config/config.php';
require_once 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$q = isset($_GET['q']) ? $_GET['q'] : '';
$params = explode('/', trim($q, '/'));
$type = isset($params[0]) ? $params[0] : '';
$id = isset($params[1]) ? $params[1] : null;

// ==================== GET ====================
if ($method === 'GET') {
    if ($type === 'workouts') {
        if (isset($id)) {
            getWorkout($pdo, $id);
        } else {
            getWorkouts($pdo);
        }
    }
    if ($type === 'users') {
        if (isset($id)) {
            getUser($pdo, $id);
        } else {
            getUsers($pdo);
        }
    }
    if ($type === 'exercises') {
        getExercises($pdo);
    }
    if ($type === 'sets') {
        if (isset($id)) {
            getSets($pdo, $id);
        }
    }
}

// ==================== POST ====================
if ($method === 'POST') {
    if ($type === 'workouts') {
        addWorkout($pdo, $_POST);
    }
    if ($type === 'users') {
        addUser($pdo, $_POST);
    }
    if ($type === 'sets') {
        $data = json_decode(file_get_contents('php://input'), true);
        addSet($pdo, $data);
    }
}

// ==================== PUT ====================
if ($method === 'PUT') {
    if (isset($id)) {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($type === 'workouts') {
            updateWorkout($pdo, $id, $data);
        }
        if ($type === 'users') {
            updateUser($pdo, $id, $data);
        }
        if ($type === 'sets') {
            updateSet($pdo, $id, $data);
        }
    }
}

// ==================== DELETE ====================
if ($method === 'DELETE') {
    if ($type === 'workouts') {
        if (isset($id)) {
            deleteWorkout($pdo, $id);
        }
    }
    if ($type === 'users') {
        if (isset($id)) {
            deleteUser($pdo, $id);
        }
    }
    if ($type === 'sets') {
        if (isset($id)) {
            deleteSet($pdo, $id);
        }
    }
}

// ==================== 404 ====================
if (!isset($response_sent)) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
?>