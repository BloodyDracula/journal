<?php
global $pdo;
require 'db.php'; // Подключение к базе данных

// Получение данных из формы
$login = $_POST['login'];
$password = $_POST['password'];

try {
    // Запрос к базе для проверки пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) { // Если используется хэширование, замените на password_verify()
        session_start();
        $_SESSION['user'] = $user;

        // Перенаправление в зависимости от роли
        if ($user['role'] === 'admin') {
            header('Location: /admin');
        } elseif ($user['role'] === 'teacher') {
            header('Location: /teacher');
        } elseif ($user['role'] === 'student') {
            header('Location: /student');
        }
        exit;
    } else {
        echo "Неверный логин или пароль!";
    }
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}
?>
