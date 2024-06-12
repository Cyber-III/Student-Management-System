<?php
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'delete' button is clicked
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Delete the material
        $sql = "DELETE FROM course_materials WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $_SESSION['delete_success'] = "Material deleted successfully.";
                $stmt->close();
            } else {
                $_SESSION['error_message'] = "Error deleting material: " . $stmt->error;
            }
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
        // Redirect back to the course materials page
        header("Location: coursematerials.php");
        exit();
    }
}

// Redirect if accessed directly
header("Location: coursematerials.php");
exit();
?>
