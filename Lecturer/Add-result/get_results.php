<?php
// Include the database connection
include_once('../connection.php');

// Get the username from the query parameter
$username = $_GET['username'] ?? null;

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
} else {
    // Handle missing username parameter
    $response = ['success' => false, 'message' => 'Student ID is missing.'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
