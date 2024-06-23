<?php
// Include the database connection
include_once('../connection.php');

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $sql = "SELECT username, batch_number, module_name, assignment_name, submission_date, feedback, file_path FROM assignments WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $assignments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($assignments);
}

$conn->close();
?>
