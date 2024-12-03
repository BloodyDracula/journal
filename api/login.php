<?php
global $pdo;
require 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = isset($data['username']) ? $data['username'] : '';
$password = isset($data['password']) ? $data['password'] : '';

if (!$username || !$password) {
    echo json_encode(['error' => 'Логин и пароль обязательны']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo json_encode(['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']]);
    } else {
        echo json_encode(['error' => 'Неверный логин или пароль']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
