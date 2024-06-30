<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $award_uni = $_POST['award_uni'];
    $action = $_POST['action'];

    if ($action == 'edit') {
        // Update course details
        $sql = "UPDATE course_tbl SET course_name=?, award_uni=? WHERE course_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $course_name, $award_uni, $course_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: addCourse.php?message=edit");
        } else {
            header("Location: addCourse.php?message=edit_error");
        }
        $stmt->close();
    } elseif ($action == 'delete') {
        // Delete course
        $sql = "DELETE FROM course_tbl WHERE course_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $course_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: addCourse.php?message=delete");
        } else {
            header("Location: addCourse.php?message=delete_error");
        }
        $stmt->close();
    }
}
$conn->close();
?>
