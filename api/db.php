<?php
function connectToDatabase() {
    $host = "127.0.0.1";
    $db = "enrollment";
    $user = "root"; // Palitan kung may ibang user
    $pass = "";     // Lagay kung may password ka
    $charset = "utf8mb4";

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        throw new PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
    }
}

try {
    $pdo = connectToDatabase();
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
?>