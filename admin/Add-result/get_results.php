<?php
// Include the database connection
include_once('../connection.php');

// Get the username and batch from the query parameters
$username = $_GET['username'] ?? null;
$batch = $_GET['batch'] ?? null;

if ($username) {
    // Prepare SQL statement to fetch results for the selected student ID
    $sql = "SELECT username, batch_number, module_name, coursework_result, presentation_result, exam_result, final_result 
            FROM final_result 
            WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $results = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($results);
} elseif ($batch) {
    // Prepare SQL statement to fetch results for the selected batch
    $sql = "SELECT username, batch_number, module_name, coursework_result, presentation_result, exam_result, final_result 
            FROM final_result 
            WHERE batch_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $batch);
    $stmt->execute();
    $result = $stmt->get_result();

    $results = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    // Handle missing parameters
    $response = ['success' => false, 'message' => 'Student ID or Batch number is missing.'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
