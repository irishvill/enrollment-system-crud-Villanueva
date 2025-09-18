<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

// show errors kung may mali (pwede alisin sa production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $stmt = $pdo->query("SELECT sem_id, sem_name FROM semester_tbl ORDER BY sem_id ASC");
    $semesters = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $semesters]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>