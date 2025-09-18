<?php
require_once __DIR__ . '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['year_from'], $data['year_to'])) {
    $year_from = $data['year_from'];
    $year_to = $data['year_to'];

    try {
        $sql = "INSERT INTO year_tbl (year_from, year_to) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$year_from, $year_to]);
        echo json_encode(['success' => true, 'message' => 'Year added successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding year: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>