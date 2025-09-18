<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['stud_id'], $data['subject_id'])) {
    $stud_id = $data['stud_id'];
    $subject_id = $data['subject_id'];

    try {
        $sql = "INSERT INTO student_load (stud_id, subject_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$stud_id, $subject_id]);
        echo json_encode(['success' => true, 'message' => 'Student enrolled successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error enrolling student: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>