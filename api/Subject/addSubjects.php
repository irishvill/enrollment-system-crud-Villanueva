<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['subject_name'], $data['sem_id'])) {
    $subject_name = $data['subject_name'];
    $sem_id = $data['sem_id'];

    try {
        $sql = "INSERT INTO subject_tbl (subject_name, sem_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_name, $sem_id]);
        echo json_encode(['success' => true, 'message' => 'Subject added successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding subject: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>