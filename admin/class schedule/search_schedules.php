<?php
include_once('../connection.php');

$course = isset($_GET['course']) ? $_GET['course'] : '';
$module = isset($_GET['module']) ? $_GET['module'] : '';

$sql = "SELECT * FROM class_schedule WHERE 1=1";
$params = [];

if (!empty($course)) {
    $sql .= " AND course = ?";
    $params[] = $course;
}

if (!empty($module)) {
    $sql .= " AND module = ?";
    $params[] = $module;
}

$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['course']) . "</td>";
        echo "<td>" . htmlspecialchars($row['batch']) . "</td>";
        echo "<td>" . htmlspecialchars($row['module']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lecturer']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['time']) . "</td>";
        echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
        echo "<td>" . htmlspecialchars($row['hall']) . "</td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='delete_course' value='" . htmlspecialchars($row['course']) . "'>
                    <button type='submit' class='btn btn-danger' name='delete'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    $stmt->close();
}
?>
