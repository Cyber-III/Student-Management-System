<?php
session_start();
ob_start();  // Start output buffering

include_once('../connection.php');  // Adjust path as necessary

include_once('../../admin/assets/content/static/template.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: modules.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM modules WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $module = $result->fetch_assoc();
    $stmt->close();

    if (!$module) {
        header("Location: modules.php");
        exit();
    }
} else {
    die("Error in SQL query: " . $conn->error);
}

if (isset($_POST['update'])) {
    $course = $_POST['course'];
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $num_assignments = $_POST['num_assignments'];

    $sql = "UPDATE modules SET course = ?, module_name = ?, module_code = ?, date = ?, duration = ?, num_assignments = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssiii', $course, $module_name, $module_code, $date, $duration, $num_assignments, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['edit_success'] = "Module Edited Successfully.";
        header("Location: modules.php");
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
    <title>Edit Module</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-modules.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <h1>Edit Module</h1>
    <form method="post">
        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <input type="text" class="form-control" id="course" name="course" value="<?= htmlspecialchars($module['course']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="module_name" class="form-label">Module Name</label>
            <input type="text" class="form-control" id="module_name" name="module_name" value="<?= htmlspecialchars($module['module_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="module_code" class="form-label">Module Code</label>
            <input type="text" class="form-control" id="module_code" name="module_code" value="<?= htmlspecialchars($module['module_code']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($module['date']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Duration</label>
            <input type="text" class="form-control" id="duration" name="duration" value="<?= htmlspecialchars($module['duration']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="num_assignments" class="form-label">Number of Assignments</label>
            <input type="text" class="form-control" id="num_assignments" name="num_assignments" value="<?= htmlspecialchars($module['num_assignments']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="modules.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
