<?php
session_start();

include_once('../connection.php');

// Loading the template.php
include_once('../assests/content/static/template.php');

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session

// Fetch the user's course and batch number based on the username
$sql = "SELECT batch_number, course FROM login_tbl WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $batch_number = $user['batch_number'];
        $course = $user['course'];

        // Debugging statement to confirm course and batch_number are retrieved
        error_log("Retrieved course: " . $course);
        error_log("Retrieved batch_number: " . $batch_number);

        // Fetch the course materials based on the course and batch number
        $sql = "SELECT * FROM course_materials WHERE batch_number = ? AND course = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('is', $batch_number, $course);
            $stmt->execute();
            $result = $stmt->get_result();
            $modules = $result->fetch_all(MYSQLI_ASSOC); // Fetch all records
            $stmt->close();
        } else {
            die("Error in SQL query: " . $conn->error);
        }
    } else {
        die("User not found.");
    }
} else {
    die("Error in SQL query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Module Table Page</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="courseMateriel-style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <div class="topic">
        <h1>Course Materials</h1>
    </div>
    <div class="table-container">
        <?php if (!empty($modules)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Module Name</th>
                        <th>Module Code</th>
                        <th>Topic</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                            <td><?php echo htmlspecialchars($module['module_code']); ?></td>
                            <td><?php echo htmlspecialchars($module['topic']); ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($module['download']) ?>" class="view-link" target="_blank">Download</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <img src="Images/no_data.jpg" alt="No data available">
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
