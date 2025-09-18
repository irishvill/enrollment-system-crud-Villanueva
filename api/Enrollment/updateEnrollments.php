<?php
// Include the database connection file
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');


$data = json_decode(file_get_contents("php://input"), true);
$load_id = $data['load_id'];
$stud_id = $data['stud_id'];
$subject_id = $data['subject_id'];

try {
    $sql = "UPDATE student_load SET stud_id = ?, subject_id = ? WHERE load_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$stud_id, $subject_id, $load_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Enrollment updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or enrollment not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>