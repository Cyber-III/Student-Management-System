<?php
session_start();
ob_start();  // Start output buffering

include_once('../connection.php');
include_once('../assests/content/static/template.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: coursematerials.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM course_materials WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $module = $result->fetch_assoc();
    $stmt->close();

    if (!$module) {
        header("Location: coursematerials.php");
        exit();
    }
} else {
    die("Error in SQL query: " . $conn->error);
}

if (isset($_POST['update'])) {
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $topic = $_POST['topic'];
    $batch_number = $_POST['batch_number'];
    $course = $_POST['course'];
    $download = $_POST['download'];

    $sql = "UPDATE course_materials SET module_name = ?, module_code = ?, topic = ?, batch_number = ?, course = ?, download = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssi', $module_name, $module_code, $topic, $batch_number, $course, $download, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['edit_success'] = "Course Material Edited Successfully.";
        header("Location: coursematerials.php");
        exit();
    } else {
        die("Error in SQL query: " . $conn->error);
    }
}

ob_end_flush();  // Flush the output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course Material</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-course_materials.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <h1>Edit Course Material</h1>
    <form method="post">
        <div class="mb-3">
            <label for="module_name" class="form-label">Module Name</label>
            <input type="text" class="form-control" id="module_name" name="module_name" value="<?= htmlspecialchars($module['module_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="module_code" class="form-label">Module Code</label>
            <input type="text" class="form-control" id="module_code" name="module_code" value="<?= htmlspecialchars($module['module_code']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="topic" class="form-label">Topic</label>
            <input type="text" class="form-control" id="topic" name="topic" value="<?= htmlspecialchars($module['topic']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="batch_number" class="form-label">Batch Number</label>
            <input type="text" class="form-control" id="batch_number" name="batch_number" value="<?= htmlspecialchars($module['batch_number']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <input type="text" class="form-control" id="course" name="course" value="<?= htmlspecialchars($module['course']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="download" class="form-label">Download Link</label>
            <input type="text" class="form-control" id="download" name="download" value="<?= htmlspecialchars($module['download']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="coursematerials.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
