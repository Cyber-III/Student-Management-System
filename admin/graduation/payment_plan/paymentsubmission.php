<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $batch_no = mysqli_real_escape_string($conn, $_POST['batch_no']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $location = mysqli_real_escape_string($conn, $_POST['description']);
    $course = mysqli_real_escape_string($conn, $_POST['batch_course']);

    // Insert data into the database
    $sql = "INSERT INTO graduation (name, batch_number, date, time, location, course)
            VALUES ('$name', '$batch_no', '$date', '$time', '$location', '$course')";

    if (mysqli_query($conn, $sql)) {
        // Redirect with a success message
        header("Location: graduationSechedule.php?message=insertpayment");
        exit();
    } else {
        // Handle the error
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
