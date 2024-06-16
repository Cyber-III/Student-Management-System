<?php
include_once('../connection.php');

$course = isset($_GET['course']) ? $_GET['course'] : '';
$module_name = isset($_GET['module_name']) ? $_GET['module_name'] : '';

$sql = "SELECT * FROM modules WHERE 1=1";
$params = [];
if ($course) {
    $sql .= " AND course LIKE ?";
    $params[] = '%' . $course . '%';
}
if ($module_name) {
    $sql .= " AND module_name LIKE ?";
    $params[] = '%' . $module_name . '%';
}

$stmt = $conn->prepare($sql);
if ($stmt) {
    if ($params) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $modules = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error in SQL query: " . $conn->error;
    exit;
}

foreach ($modules as $module):
?>
    <tr>
        <td><?php echo htmlspecialchars($module['course']); ?></td>
        <td><?php echo htmlspecialchars($module['module_name']); ?></td>
        <td><?php echo htmlspecialchars($module['module_code']); ?></td>
        <td><?php echo htmlspecialchars($module['date']); ?></td>
        <td><?php echo htmlspecialchars($module['duration']); ?></td>
        <td><?php echo htmlspecialchars($module['num_assignments']); ?></td>
        <td>
            <a href="edit_module.php?id=<?= htmlspecialchars($module['id']) ?>" class="btn btn-primary">Edit</a>
        </td>
        <td>
            <form method="post" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= htmlspecialchars($module['id']) ?>">
                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
