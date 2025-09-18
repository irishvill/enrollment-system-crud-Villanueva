<?php
header('Content-Type: application/json');
require_once('../db.php');

try {
    $stmt = $pdo->query("SELECT * FROM student_tbl");
    $students = $stmt->fetchAll();
    echo json_encode(["success" => true, "data" => $students]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>