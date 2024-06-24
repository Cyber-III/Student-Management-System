<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $module_code = mysqli_real_escape_string($conn, $_POST['module_code']);
    $batch_number = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $viva_name = mysqli_real_escape_string($conn, $_POST['viva_name']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Insert the data into the database
    $sql = "INSERT INTO viva_schedules (course, module_name, module_code, batch_number, viva_name, date, location) VALUES 
    ('$course', '$module_name', '$module_code', '$batch_number', '$viva_name', '$date', '$location')";

    if (mysqli_query($conn, $sql)) {
        header("Location: viva.php?message=insert");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the form with a success message
    header("Location: viva_form.php?message=" . urlencode($message));
    exit();
}
?>
