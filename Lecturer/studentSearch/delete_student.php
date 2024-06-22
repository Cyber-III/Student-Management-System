<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username from POST data
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Create the SQL query to delete the student data
    $sql = "DELETE FROM login_tbl WHERE username = '$username'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        header("Location: studentSearch.php?message=deletedstu");
        exit();
    } else {
        // Handle the error
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
