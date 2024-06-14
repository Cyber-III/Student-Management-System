<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $batch_number = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $awarding_uni = mysqli_real_escape_string($conn, $_POST['awarding_uni']);
    $uni_number = mysqli_real_escape_string($conn, $_POST['uni_number']);
    $lec = mysqli_real_escape_string($conn, $_POST['lec']);

    // Create the SQL query to update the student data
    $sql = "UPDATE login_tbl SET
                student_name = '$student_name',
                course = '$course',
                batch_number = '$batch_number',
                gender = '$gender',
                dob = '$dob',
                nic = '$nic',
                email = '$email',
                contact = '$contact',
                awarding_uni = '$awarding_uni',
                uni_number = '$uni_number',
                lec = '$lec'
            WHERE username = '$username'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        header("Location: studentSearch.php?message=updatedstu");
        exit();
    } else {
        // Handle the error
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
