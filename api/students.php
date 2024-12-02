<?php
global $pdo;
require 'db.php';

$stmt = $pdo->query("SELECT * FROM users WHERE role = 'student'");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($students);
?>
