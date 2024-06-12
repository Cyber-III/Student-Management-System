<?php
session_start();

// Include the database connection
include_once('../../connection.php');

include_once('../../admin\assests\content\static\template.php');

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session

// Initialize the success message
$success_message = "";

// Handle form submission for adding a new course material
if (isset($_POST['add'])) {
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $topic = $_POST['topic'];
    $batch_number = $_POST['batch_number'];
    $course = $_POST['course'];
    $download = $_POST['download'];

    $sql = "INSERT INTO course_materials (module_name, module_code, topic, batch_number, course, download) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssiss', $module_name, $module_code, $topic, $batch_number, $course, $download);
        $stmt->execute();
        $stmt->close();
        // Set the success message
        $success_message = "Material uploaded successfully.";
    } else {
        $error = "Error in SQL query: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course Material</title>
    <link rel="stylesheet" href="../style-template.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Add New Course Material</h1>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="module_name" class="form-label">Module Name</label>
            <input type="text" class="form-control" id="module_name" name="module_name" required>
        </div>
        <div class="mb-3">
            <label for="module_code" class="form-label">Module Code</label>
            <input type="text" class="form-control" id="module_code" name="module_code" required>
        </div>
        <div class="mb-3">
            <label for="topic" class="form-label">Topic</label>
            <input type="text" class="form-control" id="topic" name="topic" required>
        </div>
        <div class="mb-3">
            <label for="batch_number" class="form-label">Batch Number</label>
            <input type="text" class="form-control" id="batch_number" name="batch_number" required>
        </div>
        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <input type="text" class="form-control" id="course" name="course" required>
        </div>
        <div class="mb-3">
            <label for="download" class="form-label">Download Link</label>
            <input type="text" class="form-control" id="download" name="download" required>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <button type="submit" name="add" class="btn btn-primary">Add Material</button>
        <a href="coursematerials.php" class="btn btn-primary" style="background-color: red;">Back</a>
    </form>
</div>
</body>
</html>
