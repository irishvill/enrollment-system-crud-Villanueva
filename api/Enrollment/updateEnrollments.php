<?php
// Include the database connection file
require_once __DIR__ . '../../db.php';
header('Content-Type: application/json');


// Get the raw JSON data from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Check if all required fields are set in the received data
if (isset($data['stud_id'], $data['first_name'], $data['middle_name'], $data['last_dame'], $data['program_id'], $data['allowance'])) {
    // Sanitize and assign the data to variables
    $studId = $data['stud_id'];
    $firstName = $data['first_name'];
    $middleName = $data['middle_name'];
    $lastName = $data['last_name'];
    $programId = $data['program_id'];
    $allowance = $data['allowance'];

    try {
        // SQL query to update a student's record using a prepared statement
        $sql = "UPDATE student_tbl SET `first_name` = ?, `middle_name` = ?, `last_name` = ?, program_id = ?, allowance = ? WHERE stud_id = ?";
        
        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        
        // Execute the statement with the provided values
        $stmt->execute([$firstName, $middleName, $lastName, $programId, $allowance, $studId]);

        // Return a success message in JSON format
        echo json_encode(['success' => true, 'message' => 'Student updated successfully!']);
    } catch (PDOException $e) {
        // Return an error message if the query fails
        echo json_encode(['success' => false, 'message' => 'Error updating student: ' . $e->getMessage()]);
    }
} else {
    // Return an error if the input data is incomplete or invalid
    echo json_encode(['success' => false, 'message' => 'Invalid input. All fields are required.']);
}
?>