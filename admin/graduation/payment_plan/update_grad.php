<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted values
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $batch_number = isset($_POST['batch_number']) ? mysqli_real_escape_string($conn, $_POST['batch_number']) : '';
    $date = isset($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : '';
    $time = isset($_POST['time']) ? mysqli_real_escape_string($conn, $_POST['time']) : '';
    $location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';
    $course = isset($_POST['course']) ? mysqli_real_escape_string($conn, $_POST['course']) : '';

    // Update the record
    $sql = "UPDATE graduation SET 
            name = '$name', 
            batch_number = '$batch_number', 
            date = '$date', 
            time = '$time', 
            location = '$location', 
            course = '$course' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Redirect with success message
        header("Location: graduationSechedule.php?message=updatedpay");
    } else {
        // Redirect with error message
        header("Location: graduationSechedule.php?message=error");
    }
}
?>
