<?php
global $pdo;
require 'db.php';

header('Content-Type: application/json');

$groupId = $_GET['id'];

if (!$groupId) {
    echo json_encode(['error' => 'ID группы не указан']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT u.id, u.name, sg.grade, sg.attendance 
        FROM users u
        INNER JOIN students_groups sg ON sg.student_id = u.id
        WHERE sg.group_id = :groupId
    ');
    $stmt->execute(['groupId' => $groupId]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($students);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
