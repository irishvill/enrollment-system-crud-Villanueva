<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$program_id = $data['program_id'];
$program_name = $data['program_name'];
$ins_id = $data['ins_id'];

try {
    $sql = "UPDATE program_tbl SET program_name = ?, ins_id = ? WHERE program_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$program_name, $ins_id, $program_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Program updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or program not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>