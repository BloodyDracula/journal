<?php
global $pdo;
require 'db.php'; // Убедитесь, что здесь правильно подключена база данных

header('Content-Type: application/json');

$teacherId = $_GET['id']; // Получаем ID преподавателя из URL

if (!$teacherId) {
    echo json_encode(['error' => 'ID преподавателя не указан']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT g.id, g.name 
        FROM groups g
        INNER JOIN teachers_groups tg ON tg.group_id = g.id
        WHERE tg.teacher_id = :teacherId
    ');
    $stmt->execute(['teacherId' => $teacherId]);
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($groups);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
