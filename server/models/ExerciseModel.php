<?php
require './config/config.php';

function getAllExercises($pdo) {
    $stmt = $pdo->query("SELECT * FROM exercises");
    return $stmt->fetchAll();
}
?>