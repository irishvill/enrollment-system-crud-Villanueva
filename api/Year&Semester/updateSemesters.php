<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sem_id'], $data['sem_name'], $data['year_id'])) {
    $sem_id = $data['sem_id'];
    $sem_name = $data['sem_name'];
    $year_id = $data['year_id'];

    try {
        $sql = "UPDATE semester_tbl SET sem_name = ?, year_id = ? WHERE sem_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sem_name, $year_id, $sem_id]);
        echo json_encode(['success' => true, 'message' => 'Semester updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating semester: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>