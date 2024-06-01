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

// Fetch the user's course based on the username
$sql = "SELECT course, batch_number FROM login_tbl WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        $course = $user['course'];
        $batch_number = $user['batch_number'];

        // Fetch the schedule details based on the course and batch_number
        $sql = "SELECT * FROM course_materials WHERE course = ? AND batch_number = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('si', $course, $batch_number);
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
        <h1>Course Materiels</h1>
    </div>
    <div class="table-container">
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
    </div>
</div>
</body>
</html>
