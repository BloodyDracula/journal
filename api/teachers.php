<?php
global $pdo;
require 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'teacher'");
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($teachers)) {
        echo "Преподаватели не найдены.";
    } else {
        echo json_encode($teachers);
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
