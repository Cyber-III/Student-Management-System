<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_number = $_POST['batch_number'];
    $assignment_name = $_POST['assignment_name'];
    $action = $_POST['action'];

    if ($action === 'edit') {
        $module_name = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $date_of_issue = $_POST['date_of_issue'];
        $date_of_submit = $_POST['date_of_submit'];
        $view = $_POST['view'];
        $allow_submission = isset($_POST['allow_submission']) ? 1 : 0;

        $sql = "UPDATE assignment_schedule SET module_name='$module_name', module_code='$module_code', date_of_issue='$date_of_issue', date_of_submit='$date_of_submit', view='$view', allow_submission='$allow_submission' WHERE batch_number='$batch_number' AND assignment_name='$assignment_name'";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: assignment_schedule.php?message=updated");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating record: " . mysqli_error($conn);
        }
    } elseif ($action === 'delete') {
        $sql = "DELETE FROM assignment_schedule WHERE batch_number='$batch_number' AND assignment_name='$assignment_name'";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: assignment_schedule.php?message=delete");
            exit();
        } else {
            $_SESSION['error_message'] = "Error deleting record: " . mysqli_error($conn);
        }
    }

    header('Location: assignment_schedule.php');
    exit();
}
?>
