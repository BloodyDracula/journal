<?php
global $pdo;
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $data['name'];
    $role = $data['role'];
    $stmt = $pdo->prepare("INSERT INTO users (name, role, login, password) VALUES (:name, :role, :login, :password)");
    $stmt->execute([
        'name' => $name,
        'role' => $role,
        'login' => $data['login'],
        'password' => $data['password'],
    ]);
    echo json_encode(['success' => true]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo json_encode(['success' => true]);
}
?>
