<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['year_id'])) {
    $year_id = $data['year_id'];

    try {
        // Check for related semesters
        $checkSql = "SELECT COUNT(*) FROM semester_tbl WHERE year_id = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$year_id]);
        $hasSemesters = $checkStmt->fetchColumn();

        if ($hasSemesters > 0) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete year. It has associated semesters.']);
        } else {
            $sql = "DELETE FROM year_tbl WHERE year_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$year_id]);
            echo json_encode(['success' => true, 'message' => 'Year deleted successfully!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting year: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>