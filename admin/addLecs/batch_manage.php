<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is edit
    if ($_POST['action'] === 'edit') {
        // Retrieve values from POST
        $lecturer_id = mysqli_real_escape_string($conn, $_POST['lecturer_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);

        // Perform update query
        $sql = "UPDATE lecturers SET 
                name = '$name', 
                password = '$password', 
                department = '$department', 
                email = '$email', 
                dob = '$dob', 
                gender = '$gender', 
                nic = '$nic', 
                contact = '$contact' 
                WHERE lecturer_id = '$lecturer_id'";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: studentSearch.php?message=edit");
            exit;
        } else {
            // Redirect with error message
            header("Location: studentSearch.php?message=error");
            exit;
        }
    } elseif ($_POST['action'] === 'delete') {
        // Handle delete action
        $lecturer_id = mysqli_real_escape_string($conn, $_POST['lecturer_id']);

        // Perform delete query
        $sql = "DELETE FROM lecturers WHERE lecturer_id = '$lecturer_id'";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: studentSearch.php?message=delete");
            exit;
        } else {
            // Redirect with error message
            header("Location: studentSearch.php?message=error");
            exit;
        }
    } else {
        // Invalid action
        header("Location: studentSearch.php?message=error");
        exit;
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: studentSearch.php");
    exit;
}
?>
