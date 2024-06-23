<?php
include_once('../connection.php');

if (isset($_POST['course'])) {
    $course = $_POST['course'];
    $modules = [];
    $sql_modules = "SELECT module_name FROM modules WHERE course = ?";
    $stmt = $conn->prepare($sql_modules);
    $stmt->bind_param('s', $course);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $modules[] = $row['module_name'];
    }

    echo json_encode($modules);
}
?>
