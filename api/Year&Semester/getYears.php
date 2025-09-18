<?php
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');

try {
    // Select all data from the year table
    $stmt = $pdo->query("SELECT * FROM year_tbl");
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return a success JSON response
    echo json_encode([
        "success" => true,
        "message" => "Years retrieved successfully.",
        "data" => $years
    ]);
} catch (PDOException $e) {
    // Return an error JSON response if something goes wrong
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
