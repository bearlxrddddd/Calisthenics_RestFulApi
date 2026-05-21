<?php
require './config/config.php';

function getSetsByWorkout($pdo, $workout_id) {
    $stmt = $pdo->prepare("SELECT s.*, e.name FROM sets s JOIN exercises e ON s.exercise_id = e.id WHERE workout_id = :workout_id ORDER BY set_order");
    $stmt->execute(['workout_id' => $workout_id]);
    return $stmt->fetchAll();
}

function addSet($pdo, $workout_id, $exercise_id, $set_order, $repetitions, $weight) {
    $stmt = $pdo->prepare("INSERT INTO sets (workout_id, exercise_id, set_order, repetitions, added_weight_kg) VALUES (:workout_id, :exercise_id, :set_order, :repetitions, :weight)");
    return $stmt->execute([
        'workout_id' => $workout_id,
        'exercise_id' => $exercise_id,
        'set_order' => $set_order,
        'repetitions' => $repetitions,
        'weight' => $weight
    ]);
}

function updateSet($pdo, $id, $exercise_id, $set_order, $repetitions, $weight) {
    $stmt = $pdo->prepare("UPDATE sets SET exercise_id = :exercise_id, set_order = :set_order, repetitions = :repetitions, added_weight_kg = :weight WHERE id = :id");
    return $stmt->execute([
        'id' => $id,
        'exercise_id' => $exercise_id,
        'set_order' => $set_order,
        'repetitions' => $repetitions,
        'weight' => $weight
    ]);
}

function deleteSet($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM sets WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function getSetById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM sets WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}
?>