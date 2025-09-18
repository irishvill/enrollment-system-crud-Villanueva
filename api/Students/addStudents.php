<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

// Decode ang JSON data mula sa request body
$data = json_decode(file_get_contents('php://input'), true);

// I-check kung may natanggap na JSON data at kung ito ay valid
if ($data === null) {
    http_response_code(400);
    // Nagdagdag ng mas detalyadong mensahe para sa debugging
    echo json_encode(['success' => false, 'message' => 'Add failed: Invalid or no JSON data received. PHP input stream is empty or contains malformed JSON.']);
    exit();
}

// I-check kung kumpleto ang lahat ng kailangang data
if (empty($data['first_name']) || empty($data['middle_name']) || empty($data['last_name']) || empty($data['program_id']) || empty($data['allowance'])) {
    http_response_code(400);
    // Nagdagdag ng debugging output para makita ang natanggap na data
    echo json_encode(['success' => false, 'message' => 'Add failed: Missing required student data.', 'received_data' => $data]);
    exit();
}

try {
    // I-prepare ang SQL statement para maiwasan ang SQL injection
    $stmt = $pdo->prepare("INSERT INTO student_tbl (first_name, middle_name, last_name, program_id, allowance) VALUES (?, ?, ?, ?, ?)");
    
    // I-execute ang statement gamit ang natanggap na data
    $stmt->execute([
        $data['first_name'],
        $data['middle_name'],
        $data['last_name'],
        $data['program_id'],
        $data['allowance']
    ]);

    // Ibalik ang success response
    echo json_encode(['success' => true, 'message' => 'Student added successfully.']);
} catch (PDOException $e) {
    // Ibalik ang error response kung may nangyaring problema
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Add failed: ' . $e->getMessage()]);
}
?>
