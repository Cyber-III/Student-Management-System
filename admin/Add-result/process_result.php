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

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare SQL statement to insert or update marks in final_result table
        $sqlFinalResult = "INSERT INTO final_result (username, batch_number, module_code, module_name, coursework_result, presentation_result, exam_result, final_result) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                coursework_result = VALUES(coursework_result),
                presentation_result = VALUES(presentation_result),
                exam_result = VALUES(exam_result),
                final_result = VALUES(final_result)";

        $stmtFinalResult = $conn->prepare($sqlFinalResult);
        if ($stmtFinalResult) {
            $stmtFinalResult->bind_param('ssssssss', $studentId, $batch, $module, $module, $assignmentMarks, $presentationMarks, $examMarks, $finalMarks);
            $stmtFinalResult->execute();

            // Check if insertion or update was successful
            if ($stmtFinalResult->affected_rows > 0) {
                // Prepare SQL statement to update assignment marks in assignments table
                $sqlAssignment = "UPDATE assignments SET results = ? WHERE username = ? AND batch_number = ? AND module_name = ?";
                $stmtAssignment = $conn->prepare($sqlAssignment);
                if ($stmtAssignment) {
                    $stmtAssignment->bind_param('ssss', $assignmentMarks, $studentId, $batch, $module);
                    $stmtAssignment->execute();

                    // Check if update was successful
                    if ($stmtAssignment->affected_rows > 0) {
                        $response = ['success' => true, 'message' => "Result for $studentName ($studentId) has been successfully saved."];
                    } else {
                        throw new Exception("Failed to update assignment result for $studentName ($studentId). Please try again.");
                    }

                    // Close statement
                    $stmtAssignment->close();
                } else {
                    throw new Exception("Database error: " . $conn->error);
                }
            } else {
                throw new Exception("Failed to save final result for $studentName ($studentId). Please try again.");
            }

            // Close statement
            $stmtFinalResult->close();
        } else {
            throw new Exception("Database error: " . $conn->error);
        }

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        $conn->rollback();
        $response = ['success' => false, 'message' => $e->getMessage()];
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
