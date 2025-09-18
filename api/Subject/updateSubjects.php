<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$subject_id = $data['subject_id'];
$subject_name = $data['subject_name'];
$sem_id = $data['sem_id'];

try {
    $sql = "UPDATE subject_tbl SET subject_name = ?, sem_id = ? WHERE subject_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subject_name, $sem_id, $subject_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Subject updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or subject not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>