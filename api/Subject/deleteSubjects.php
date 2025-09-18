<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['subject_id'])) {
    $subject_id = $data['subject_id'];

    try {
        // Check for related student loads
        $checkSql = "SELECT COUNT(*) FROM student_load WHERE subject_id = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$subject_id]);
        $hasEnrollments = $checkStmt->fetchColumn();

        if ($hasEnrollments > 0) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete subject. Students are enrolled in it.']);
        } else {
            $sql = "DELETE FROM subject_tbl WHERE subject_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$subject_id]);
            echo json_encode(['success' => true, 'message' => 'Subject deleted successfully!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting subject: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>