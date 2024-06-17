<?php
session_start();
include_once('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM class_schedule WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $_SESSION['delete_success'] = "Class schedule deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error deleting class schedule: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
        header("Location: class_schedule.php");
        exit();
    }
}

// Redirect if accessed directly
header("Location: class_schedule.php");
exit();
?>
