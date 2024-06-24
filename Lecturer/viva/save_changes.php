<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
    $viva_name = isset($_POST['viva_name']) ? htmlspecialchars($_POST['viva_name']) : '';
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';
    $time_slot_start = isset($_POST['time_slot_start']) ? htmlspecialchars($_POST['time_slot_start']) : '';
    $time_slot_end = isset($_POST['time_slot_end']) ? htmlspecialchars($_POST['time_slot_end']) : '';
    $classroom = isset($_POST['classroom']) ? htmlspecialchars($_POST['classroom']) : '';

    // Update the database
    $query = "UPDATE team_members SET viva_name = ?, username = ?, name = ?, date = ?, time_slot_start = ?, time_slot_end = ?, classroom = ? WHERE id = ? AND username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssssssis', $viva_name, $username, $name, $date, $time_slot_start, $time_slot_end, $classroom, $id, $username);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: team.php?message=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);
?>
