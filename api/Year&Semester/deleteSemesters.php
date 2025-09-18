<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$sem_id = $data['sem_id'];

if (empty($sem_id)) {
    echo json_encode(["success" => false, "message" => "Semester ID is required."]);
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM semester_tbl WHERE sem_id = ?");
    $stmt->execute([$sem_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Semester deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Semester not found or could not be deleted."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>