<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';

    // Delete the record from the database
    $query = "DELETE FROM team_members WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: team.php?message=delete");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);
?>
