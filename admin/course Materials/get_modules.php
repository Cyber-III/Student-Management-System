<?php
include_once('../connection.php');

if (isset($_POST['course'])) {
    $course = $_POST['course'];

    $sql_modules = "SELECT module_name, module_code FROM modules WHERE course = ?";
    $stmt = $conn->prepare($sql_modules);
    $stmt->bind_param('s', $course);
    $stmt->execute();
    $result = $stmt->get_result();

    $modules = [];
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }

    $stmt->close();

    if (!empty($modules)) {
        foreach ($modules as $module) {
            echo '<option value="' . htmlspecialchars($module['module_name']) . '" data-code="' . htmlspecialchars($module['module_code']) . '">' . htmlspecialchars($module['module_name']) . '</option>';
        }
    } else {
        echo '<option value="">No modules available</option>';
    }
}
?>
