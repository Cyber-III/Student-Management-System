<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $batch_number = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $viva_name = mysqli_real_escape_string($conn, $_POST['viva_name']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $allow_submission = isset($_POST['allow_submission']) ? 1 : 0;
    $action = $_POST['action'];

    // Perform the corresponding action based on the value of 'action'
    if ($action == 'edit') {
        // Update the record
        $sql = "UPDATE viva_schedules SET viva_name='$viva_name', date='$date', location='$location', allow_submission='$allow_submission' WHERE course='$course' AND batch_number='$batch_number'";
        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: viva.php?message=updated");
            exit();
        } else {
            // Handle error
            echo "Error updating record: " . mysqli_error($conn);
        }
    } elseif ($action == 'delete') {
        // Delete the record
        $sql = "DELETE FROM viva_schedules WHERE course='$course' AND batch_number='$batch_number'";
        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: viva.php?message=delete");
        } else {
            // Handle error
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
