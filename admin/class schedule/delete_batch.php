<?php
session_start();
include_once('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['batch'])) {
        $batch = $_POST['batch'];

        // Prepare statement to delete schedules for the selected batch
        $sql = "DELETE FROM class_schedule WHERE batch = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $batch);
            if ($stmt->execute()) {
                echo "Batch '$batch' schedules deleted successfully.";
            } else {
                echo "Error deleting batch schedules: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error in SQL query: " . $conn->error;
        }
    }
}
?>
