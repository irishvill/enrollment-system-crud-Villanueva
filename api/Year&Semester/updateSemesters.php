<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$sem_id = $data['sem_id'];
$sem_name = $data['sem_name'];
$year_id = $data['year_id'];

try {
    $sql = "UPDATE semester_tbl SET sem_name = ?, year_id = ? WHERE sem_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sem_name, $year_id, $sem_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Semester updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or semester not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>