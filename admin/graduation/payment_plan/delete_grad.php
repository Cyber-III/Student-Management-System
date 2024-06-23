<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted values
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Delete the record
    $sql = "DELETE FROM graduation WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Redirect with success message
        header("Location: plan.php?message=delete");
    } else {
        // Redirect with error message
        header("Location: plan.php?message=error");
    }
}
?>
