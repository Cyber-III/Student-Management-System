<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

if (isset($_GET['course'])) {
    $course = $_GET['course'];

    // Fetch batches based on the selected course
    $sql = "SELECT DISTINCT batch_no FROM batches WHERE course = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $course);
    $stmt->execute();
    $result = $stmt->get_result();

    $batches = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row['batch_no'];
        }
    }

    echo json_encode($batches);

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
