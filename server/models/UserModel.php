<?php
require './config/config.php';

function getAllUsers($pdo) {
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll();
}

function getUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function getUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

function createUser($pdo, $username, $email, $weight, $height) {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, weight_kg, height_cm) VALUES (:username, :email, :weight, :height)");
    return $stmt->execute([
        'username' => $username,
        'email' => $email,
        'weight' => $weight,
        'height' => $height
    ]);
}

function updateUser($pdo, $id, $username, $email, $weight, $height) {
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, weight_kg = :weight, height_cm = :height WHERE id = :id");
    return $stmt->execute([
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'weight' => $weight,
        'height' => $height
    ]);
}

function deleteUser($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function loginUser($pdo, $email, $password) {
    $user = getUserByEmail($pdo, $email);
    
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

function registerUser($pdo, $username, $email, $password, $weight, $height) {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, weight_kg, height_cm) VALUES (:username, :email, :password, :weight, :height)");
    return $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'weight' => $weight,
        'height' => $height
    ]);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logoutUser() {
    session_destroy();
}
?>