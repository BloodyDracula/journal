<?php
$host = 'localhost'; // хост, на котором работает MySQL (localhost по умолчанию)
$dbname = 'journal'; // имя базы данных
$username = 'root'; // имя пользователя для базы данных (по умолчанию 'root' для XAMPP)
$password = ''; // пароль (по умолчанию пусто для XAMPP)

try {
    // Создаем объект PDO для подключения к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Устанавливаем режим ошибок для вывода исключений
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Успешное подключение
    // echo "Подключение прошло успешно!";
} catch (PDOException $e) {
    // В случае ошибки подключение не удалось
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
