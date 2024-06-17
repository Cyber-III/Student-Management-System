<?php
session_start();
include_once('../connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['delete'])) {
    $id = $_POST['c'];
    
    $sql = "DELETE FROM class_schedule WHERE course = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $course);
        if ($stmt->execute()) {
            $_SESSION['delete_success'] = "Class schedule deleted successfully.";
        } else {
            $_SESSION['delete_error'] = "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['delete_error'] = "Error in SQL query preparation: " . $conn->error;
    }
    header("Location: modules.php");
    exit();
}
?>
