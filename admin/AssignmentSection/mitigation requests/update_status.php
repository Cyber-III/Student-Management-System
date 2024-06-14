<?php
// Include the database connection
include_once('../../connection.php');

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    // Get the POST data
    $id = sanitize_input($_POST["id"]);
    $status = sanitize_input($_POST["status"]);

    // Update the mitigation status in the database
    $stmt = $conn->prepare("UPDATE mitigations SET status = ? WHERE id = ?");
    if ($stmt === false) {
        echo json_encode(array('status' => 'error', 'message' => 'Prepare failed: ' . htmlspecialchars($conn->error)));
        exit();
    }
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(array('status' => 'success'));
        exit();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error updating status.'));
        exit();
    }
}
?>
