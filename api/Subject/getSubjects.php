<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM subject_tbl ORDER BY subject_id ASC");
    $rows = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
