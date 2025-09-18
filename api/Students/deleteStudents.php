<?php
header('Content-Type: application/json');
require_once('../db.php');

try {
    // Decode the JSON data sent from the front-end
    $data = json_decode(file_get_contents("php://input"));

    // Check if the data is valid and not null
    if ($data === null || !isset($data->stud_id)) {
        throw new Exception("Invalid or no student ID provided.");
    }

    $sql = "DELETE FROM student_tbl WHERE stud_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data->stud_id]);

    echo json_encode(["success" => true, "message" => "Student deleted successfully."]);

} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        echo json_encode(["success" => false, "message" => "Cannot delete student because they are currently enrolled in subjects."]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Delete failed: " . $e->getMessage()]);
}
?>