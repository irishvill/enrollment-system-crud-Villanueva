<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data['program_id']) || empty($data['program_name']) || empty($data['ins_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}
try {
    $stmt = $conn->prepare("UPDATE program_tbl SET program_name=?, ins_id=? WHERE program_id=?");
    $stmt->execute([$data['program_name'], $data['ins_id'], $data['program_id']]);
    echo json_encode(['success' => true, 'message' => 'Program updated successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>