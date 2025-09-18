<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sem_id'])) {
    $sem_id = $data['sem_id'];

    try {
        // Check for related subjects
        $checkSql = "SELECT COUNT(*) FROM subject_tbl WHERE sem_id = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$sem_id]);
        $hasSubjects = $checkStmt->fetchColumn();

        if ($hasSubjects > 0) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete semester. It has associated subjects.']);
        } else {
            $sql = "DELETE FROM semester_tbl WHERE sem_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$sem_id]);
            echo json_encode(['success' => true, 'message' => 'Semester deleted successfully!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting semester: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>