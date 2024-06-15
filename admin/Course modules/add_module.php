<?php
session_start();

// Include the database connection
include_once('../connection.php');
include_once('../../admin/assests/content/static/template.php');

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session

// Initialize the success message
$success_message = "";

// Handle form submission for adding a new module
if (isset($_POST['add'])) {
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $num_assignments = $_POST['num_assignments'];

    $sql = "INSERT INTO modules (module_name, module_code, date, duration, num_assignments) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssss', $module_name, $module_code, $date, $duration, $num_assignments);
        $stmt->execute();
        $stmt->close();
        // Set the success message
        $success_message = "Module added successfully.";
    } else {
        $error = "Error in SQL query: " . $conn->error;
    }
}
?>
