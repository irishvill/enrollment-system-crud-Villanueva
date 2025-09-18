<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$year_id = $data['year_id'];
$year_from = $data['year_from'];
$year_to = $data['year_to'];

try {
    $sql = "UPDATE year_tbl SET year_from = ?, year_to = ? WHERE year_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$year_from, $year_to, $year_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Year updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or year not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>