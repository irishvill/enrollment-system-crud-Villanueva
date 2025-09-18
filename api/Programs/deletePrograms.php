<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data['program_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Program ID is required.']);
    exit();
}
try {
    $stmt = $conn->prepare("DELETE FROM program_tbl WHERE program_id=?");
    $stmt->execute([$data['program_id']]);
    echo json_encode(['success' => true, 'message' => 'Program deleted successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete program: ' . $e->getMessage()]);
}
?>