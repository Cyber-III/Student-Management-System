<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Fetch form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $_POST['course'];
    $batch = $_POST['batch'];
    $semester = $_POST['semester'];
    $module = $_POST['module'];
    $studentId = $_POST['studentId'];
    $studentName = $_POST['studentName'];
    $assignmentMarks = $_POST['assignmentMarks'];
    $presentationMarks = $_POST['presentationMarks'];
    $examMarks = $_POST['examMarks'];
    $finalMarks = $_POST['finalMarks'];

    // Prepare SQL statement to insert or update marks in final_result table
    $sql = "INSERT INTO final_result (username, batch_number, module_code, module_name, coursework_result, presentation_result, exam_result, final_result) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            coursework_result = VALUES(coursework_result),
            presentation_result = VALUES(presentation_result),
            exam_result = VALUES(exam_result),
            final_result = VALUES(final_result)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssss', $studentId, $batch, $module, $module, $assignmentMarks, $presentationMarks, $examMarks, $finalMarks);
        $stmt->execute();

        // Check if insertion or update was successful
        if ($stmt->affected_rows > 0) {
            $response = ['success' => true, 'message' => "Result for $studentName ($studentId) has been successfully saved."];
        } else {
            $response = ['success' => false, 'message' => "Failed to save result for $studentName ($studentId). Please try again."];
        }

        // Close statement
        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => "Database error: " . $conn->error];
    }

    // Close connection
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    // Handle non-POST requests appropriately
    $response = ['success' => false, 'message' => "Invalid request method. Please submit the form."];

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
