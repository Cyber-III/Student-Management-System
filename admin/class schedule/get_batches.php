<?php
include_once('../connection.php');

if (isset($_POST['course'])) {
    $course = $_POST['course'];
    
    $sql_batches = "SELECT DISTINCT batch_number FROM login_tbl WHERE course = ?";
    $stmt = $conn->prepare($sql_batches);
    if ($stmt) {
        $stmt->bind_param('s', $course);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $batches = [];
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row['batch_number'];
        }
        
        echo json_encode($batches);
        
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error in SQL query preparation: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Course not specified']);
}
?>
