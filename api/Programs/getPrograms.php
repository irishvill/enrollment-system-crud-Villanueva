<?php
// api/getPrograms.php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT p.program_id, p.program_name, p.ins_id, i.ins_name
                         FROM program_tbl p
                         LEFT JOIN institute_tbl i ON p.ins_id = i.ins_id
                         ORDER BY p.program_name");
    $programs = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $programs]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>