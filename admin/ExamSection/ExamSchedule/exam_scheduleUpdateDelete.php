<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_number = $_POST['batch_number'];
    $exam_name = $_POST['exam_name'];
    $action = $_POST['action'];

    if ($action === 'edit') {
        $exam_name = $_POST['exam_name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $hours = $_POST['hours'];
        $allow_submission = isset($_POST['allow_submission']) ? 1 : 0;

        $sql = "UPDATE exam_schedule SET exam_name='$exam_name', date='$date', time='$time', location='$location', hours='$hours', allow_submission='$allow_submission' WHERE batch_number='$batch_number' AND exam_name='$exam_name'";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: exam_schedule.php?message=updated");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating record: " . mysqli_error($conn);
        }
    } elseif ($action === 'delete') {
        $sql = "DELETE FROM exam_schedule WHERE batch_number='$batch_number' AND exam_name='$exam_name'";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: exam_schedule.php?message=delete");
            exit();
        } else {
            $_SESSION['error_message'] = "Error deleting record: " . mysqli_error($conn);
        }
    }

    header('Location: exam_schedule.php');
    exit();
}
?>
