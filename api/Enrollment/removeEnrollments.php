<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['load_id'])) {
    $load_id = $data['load_id'];

    try {
        $sql = "DELETE FROM student_load WHERE load_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$load_id]);
        echo json_encode(['success' => true, 'message' => 'Enrollment removed successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error removing enrollment: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>