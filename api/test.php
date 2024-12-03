<?php
global $pdo;
require 'db.php';

$stmt = $pdo->query("SELECT 'Соединение с базой установлено!' AS message");
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo $result['message'];
