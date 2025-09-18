<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

$json_data = file_get_contents("php://input");

// Decode ang JSON data
$data = json_decode($json_data, true);

// I-check kung may laman ang data at tama ang JSON format
if ($json_data === null || $json_data === false || $data === null) {
    echo json_encode(["success" => false, "message" => "Add failed: Invalid or no JSON data received."]);
    exit();
}

// I-verify ang kinakailangang fields
if (empty($data['first_name']) || empty($data['last_name']) || empty($data['program_id']) || empty($data['allowance'])) {
    echo json_encode(["success" => false, "message" => "Add failed: Missing required fields."]);
    exit();
}

$first_name = $data['first_name'];
$middle_name = $data['middle_name'] ?? ''; // Gamitin ang null coalescing operator para sa optional field
$last_name = $data['last_name'];
$program_id = $data['program_id'];
$allowance = $data['allowance'];

try {
    $sql = "INSERT INTO student_tbl (first_name, middle_name, last_name, program_id, allowance) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $middle_name, $last_name, $program_id, $allowance]);
    
    echo json_encode(["success" => true, "message" => "Student added successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>