<?php
// api/getPrograms.php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM program_tbl");
    $programs = $stmt->fetchAll();
    echo json_encode(["success" => true, "data" => $programs]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>