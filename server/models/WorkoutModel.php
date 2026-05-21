<?php
require './config/config.php';

function getAllWorkouts($pdo) {
    $stmt = $pdo->query("SELECT * FROM workouts ORDER BY workout_date DESC");
    return $stmt->fetchAll();
}

function getWorkoutById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM workouts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function createWorkout($pdo, $user_id, $date, $duration, $notes) {
    $stmt = $pdo->prepare("INSERT INTO workouts (user_id, workout_date, duration_minutes, notes) VALUES (:user_id, :date, :duration, :notes)");
    return $stmt->execute([
        'user_id' => $user_id,
        'date' => $date,
        'duration' => $duration,
        'notes' => $notes
    ]);
}

function updateWorkout($pdo, $id, $date, $duration, $notes) {
    $stmt = $pdo->prepare("UPDATE workouts SET workout_date = :date, duration_minutes = :duration, notes = :notes WHERE id = :id");
    return $stmt->execute([
        'id' => $id,
        'date' => $date,
        'duration' => $duration,
        'notes' => $notes
    ]);
}

function deleteWorkout($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM workouts WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function deleteWorkoutSets($pdo, $workout_id) {
    $stmt = $pdo->prepare("DELETE FROM sets WHERE workout_id = :workout_id");
    return $stmt->execute(['workout_id' => $workout_id]);
}
?>