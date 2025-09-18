<?php
header('Content-Type: application/json');
require_once('../db.php');

try {
    // Decode the JSON data sent from the front-end
    $data = json_decode(file_get_contents("php://input"));

    // Check if the data is valid and not null
    if ($data === null) {
        throw new Exception("Invalid or no JSON data received.");
    }

    // Check for the required fields
    if (!isset($data->stud_id) || !isset($data->first_name) || !isset($data->last_name) || !isset($data->program_id) || !isset($data->allowance)) {
        throw new Exception("Missing required fields.");
    }

    $sql = "UPDATE student_tbl SET first_name = ?, middle_name = ?, last_name = ?, program_id = ?, allowance = ? WHERE stud_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->first_name,
        $data->middle_init,
        $data->last_name,
        $data->program_id,
        $data->allowance,
        $data->stud_id
    ]);

    echo json_encode(["success" => true, "message" => "Student updated successfully."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Update failed: " . $e->getMessage()]);
}
?>