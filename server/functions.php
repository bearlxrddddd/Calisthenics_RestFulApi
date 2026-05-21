<?php

//  WORKOUTS 

function getWorkouts($pdo) {
    $sql = "SELECT * FROM workouts ORDER BY workout_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'data' => $workouts
    ]);
}

function getWorkout($pdo, $id) {
    $sql = "SELECT * FROM workouts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode([
            'status' => false,
            'message' => 'Workout not found'
        ]);
    } else {
        $workout = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode([
            'status' => true,
            'data' => $workout
        ]);
    }
}

function addWorkout($pdo, $data) {
    $sql = "INSERT INTO workouts (user_id, workout_date, duration_minutes, notes) VALUES (:user_id, :workout_date, :duration_minutes, :notes)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $data['user_id'],
        'workout_date' => $data['workout_date'],
        'duration_minutes' => $data['duration'],
        'notes' => $data['notes']
    ]);
    
    http_response_code(201);
    echo json_encode([
        'status' => true,
        'workout_id' => $pdo->lastInsertId()
    ]);
}

function updateWorkout($pdo, $id, $data) {
    $sql = "UPDATE workouts SET workout_date = :workout_date, duration_minutes = :duration, notes = :notes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'workout_date' => $data['workout_date'],
        'duration' => $data['duration'],
        'notes' => $data['notes']
    ]);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'update'
    ]);
}

function deleteWorkout($pdo, $id) {
    $sqlSets = "DELETE FROM sets WHERE workout_id = :id";
    $stmtSets = $pdo->prepare($sqlSets);
    $stmtSets->execute(['id' => $id]);
    
    $sql = "DELETE FROM workouts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'deleted'
    ]);
}

//  USERS 

function getUsers($pdo) {
    $sql = "SELECT id, username, email, weight_kg, height_cm, experience_level, role FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'data' => $users
    ]);
}

function getUser($pdo, $id) {
    $sql = "SELECT id, username, email, weight_kg, height_cm, experience_level, role FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode([
            'status' => false,
            'message' => 'User not found'
        ]);
    } else {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode([
            'status' => true,
            'data' => $user
        ]);
    }
}

function addUser($pdo, $data) {
    $sql = "INSERT INTO users (username, email, weight_kg, height_cm) VALUES (:username, :email, :weight_kg, :height_cm)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'username' => $data['username'],
        'email' => $data['email'],
        'weight_kg' => $data['weight'],
        'height_cm' => $data['height']
    ]);
    
    http_response_code(201);
    echo json_encode([
        'status' => true,
        'user_id' => $pdo->lastInsertId()
    ]);
}

function updateUser($pdo, $id, $data) {
    $sql = "UPDATE users SET username = :username, email = :email, weight_kg = :weight, height_cm = :height WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'username' => $data['username'],
        'email' => $data['email'],
        'weight' => $data['weight'],
        'height' => $data['height']
    ]);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'update'
    ]);
}

function deleteUser($pdo, $id) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'deleted'
    ]);
}

//  EXERCISES 

function getExercises($pdo) {
    $sql = "SELECT * FROM exercises ORDER BY muscle_group";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'data' => $exercises
    ]);
}

//  SETS 

function getSets($pdo, $workout_id) {
    $sql = "SELECT s.*, e.name as exercise_name 
            FROM sets s 
            JOIN exercises e ON s.exercise_id = e.id 
            WHERE workout_id = :workout_id 
            ORDER BY set_order";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['workout_id' => $workout_id]);
    $sets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'data' => $sets
    ]);
}

function addSet($pdo, $data) {
    $sql = "INSERT INTO sets (workout_id, exercise_id, set_order, repetitions, added_weight_kg) 
            VALUES (:workout_id, :exercise_id, :set_order, :repetitions, :weight)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'workout_id' => $data['workout_id'],
        'exercise_id' => $data['exercise_id'],
        'set_order' => $data['set_order'],
        'repetitions' => $data['repetitions'],
        'weight' => $data['weight']
    ]);
    
    http_response_code(201);
    echo json_encode([
        'status' => true,
        'set_id' => $pdo->lastInsertId()
    ]);
}

function updateSet($pdo, $id, $data) {
    $sql = "UPDATE sets SET exercise_id = :exercise_id, set_order = :set_order, repetitions = :repetitions, added_weight_kg = :weight WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'exercise_id' => $data['exercise_id'],
        'set_order' => $data['set_order'],
        'repetitions' => $data['repetitions'],
        'weight' => $data['weight']
    ]);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'update'
    ]);
}

function deleteSet($pdo, $id) {
    $sql = "DELETE FROM sets WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'deleted'
    ]);
}
