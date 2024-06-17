<?php
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'delete' button is clicked
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Delete the module
        $sql = "DELETE FROM modules WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $_SESSION['delete_success'] = "Module deleted successfully.";
                $stmt->close();
            } else {
                $_SESSION['error_message'] = "Error deleting module: " . $stmt->error;
            }
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
        // Redirect back to the modules page
        header("Location: modules.php");
        exit();
    }
}

// Redirect if accessed directly
header("Location: modules.php");
exit();
?>
