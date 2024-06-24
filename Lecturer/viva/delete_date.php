<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $viva_name = isset($_POST['viva_name']) ? htmlspecialchars($_POST['viva_name']) : '';
    $current_date = isset($_POST['current_date']) ? htmlspecialchars($_POST['current_date']) : '';

    // Delete the records from the database
    $query = "DELETE FROM team_members WHERE viva_name = ? AND date = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $viva_name, $current_date);

    if (mysqli_stmt_execute($stmt)) {
        echo "Records deleted successfully.";
    } else {
        echo "Error deleting records: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);
?>
