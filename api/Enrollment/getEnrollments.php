<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT sl.load_id,
               CONCAT(s.first_name, ' ', s.last_name) AS student_name,
               sub.subject_name
        FROM student_load sl
        JOIN student_tbl s ON sl.stud_id = s.stud_id
        JOIN subject_tbl sub ON sl.subject_id = sub.subject_id
    ");

    echo json_encode([
        "success" => true,
        "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
