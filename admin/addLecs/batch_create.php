<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is insert (Add Course)
    if ($_POST['action'] === 'insert') {
        // Retrieve values from POST
        $lecturer_id = mysqli_real_escape_string($conn, $_POST['username']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);

        // Perform insert query
        $sql = "INSERT INTO lecturers (username, name, password, department, email, dob, gender, nic, contact) 
                VALUES ('$lecturer_id', '$name', '$password', '$department', '$email', '$dob', '$gender', '$nic', '$contact')";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: studentSearch.php?message=insert");
            exit;
        } else {
            // Redirect with error message and SQL error details
            header("Location: studentSearch.php?message=error&error=" . urlencode(mysqli_error($conn)));
            exit;
        }
    } else {
        // Invalid action
        header("Location: studentSearch.php?message=error&action=invalid");
        exit;
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: studentSearch.php?message=error&access=direct");
    exit;
}
?>
