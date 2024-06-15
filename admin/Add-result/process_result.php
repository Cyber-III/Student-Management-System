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
    $stmt->bind_param('ssssssss', $studentId, $batch, $module, $module, $assignmentMarks, $presentationMarks, $examMarks, $finalMarks);
    $stmt->execute();

    // Check if insertion or update was successful
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Result for $studentName ($studentId) has been successfully saved.";
    } else {
        $_SESSION['error'] = "Failed to save result for $studentName ($studentId). Please try again.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to add-mark.php or appropriate page
    header("Location: add-mark.php?course=$course&batch=$batch&semester=$semester");
    exit();
} else {
    // Handle non-POST requests appropriately
    $_SESSION['error'] = "Invalid request method. Please submit the form.";
    header("Location: add-mark.php"); // Redirect to appropriate error handling page
    exit();
}
?>
