<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $batch_no = $_POST['batch_no'];
    $course = $_POST['course'];
    $intake = $_POST['intake'];
    $commencement_date = $_POST['commencement_date'];
    $award_uni = $_POST['award_uni'];
    $action = $_POST['action'];

    if ($action == 'edit') {
        // Update batch details
        $sql = "UPDATE batches SET course=?, intake=?, commencement_date=?, award_uni=? WHERE batch_no=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $course, $intake, $commencement_date, $award_uni, $batch_no);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: studentSearch.php?message=edit");
        } else {
            header("Location: studentSearch.php?message=edit_error");
        }
        $stmt->close();
    } elseif ($action == 'delete') {
        // Delete batch
        $sql = "DELETE FROM batches WHERE batch_no=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $batch_no);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: studentSearch.php?message=delete");
        } else {
            header("Location: studentSearch.php?message=delete_error");
        }
        $stmt->close();
    }
}
$conn->close();
?>
