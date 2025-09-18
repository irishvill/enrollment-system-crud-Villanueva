<?php
header('Content-Type: application/json');
require_once('../db.php');

try {
    $sql = "SELECT s.*, p.program_name FROM student_tbl s JOIN program_tbl p ON s.program_id = p.program_id ORDER BY stud_id DESC";
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll();

    echo json_encode(["success" => true, "data" => $students]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>