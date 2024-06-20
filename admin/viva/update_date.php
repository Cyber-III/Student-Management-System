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
    $new_date = isset($_POST['new_date']) ? htmlspecialchars($_POST['new_date']) : '';

    // Update the records in the database
    $query = "UPDATE team_members SET date = ? WHERE viva_name = ? AND date = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $new_date, $viva_name, $current_date);

    if (mysqli_stmt_execute($stmt)) {
        echo "Records updated successfully.";
    } else {
        echo "Error updating records: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);
?>
