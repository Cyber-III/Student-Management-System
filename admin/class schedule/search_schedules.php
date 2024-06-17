<?php
include_once('../connection.php');

// Retrieve search parameters from GET request
$course = isset($_GET['course']) ? $_GET['course'] : '';
$batch = isset($_GET['batch']) ? $_GET['batch'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// SQL query to fetch schedules with optional filtering
$sql = "SELECT * FROM class_schedule WHERE 1=1";
$params = [];

// Add filters based on the presence of each parameter
if ($course) {
    $sql .= " AND course LIKE ?";
    $params[] = '%' . $course . '%';
}
if ($batch) {
    $sql .= " AND batch LIKE ?";
    $params[] = '%' . $batch . '%';
}
if ($date) {
    $sql .= " AND date = ?";
    $params[] = $date;
}

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
if ($stmt) {
    // Bind parameters if there are any
    if ($params) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $schedules = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // Handle SQL error
    echo "Error in SQL query: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-class_schedule.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <br><br>
    <h1>Class Schedule</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Batch</th>
                <th>Module</th>
                <th>Lecturer</th>
                <th>Date</th>
                <th>Time</th>
                <th>Hall</th>
                <th>Notes</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?php echo htmlspecialchars($schedule['course']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['batch']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['module']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['lecturer']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['date']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['time']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['hall']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['notes']); ?></td>
                    <td>
                        <a href="edit_schedule.php?id=<?= htmlspecialchars($schedule['id']) ?>" class="btn btn-primary">Edit</a>
                    </td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($schedule['id']) ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
