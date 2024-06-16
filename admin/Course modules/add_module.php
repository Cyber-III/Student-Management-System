<?php
session_start();

// Include the database connection
include_once('../connection.php');

include_once('../../admin/assests/content/static/template.php');

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session

// Initialize variables
$course = $module_name = $module_code = $date = $duration = $num_assignments = "";
$success_message = $error = "";

// Fetch courses for the dropdown menu
$courses = [];
$sql_courses = "SELECT course_name FROM course_tbl";
$result_courses = $conn->query($sql_courses);
if ($result_courses) {
    while ($row = $result_courses->fetch_assoc()) {
        $courses[] = $row['course_name'];
    }
} else {
    $error = "Error fetching courses: " . $conn->error;
}

// Handle form submission for adding a new module
if (isset($_POST['add'])) {
    // Sanitize and validate inputs (assuming input validation has been done)
    $course = $_POST['course']; // Assign to $course instead of $module_name
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $num_assignments = $_POST['num_assignments'];

    // Prepare and execute SQL insertion
    $sql = "INSERT INTO modules (course, module_name, module_code, date, duration, num_assignments) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssssi', $course, $module_name, $module_code, $date, $duration, $num_assignments);
        if ($stmt->execute()) {
            $success_message = "Module added successfully.";
        } else {
            $error = "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error in SQL query preparation: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Module</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-modules.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <br><br>    
    <h1>Add Module</h1>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <?= $success_message ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="course" class="form-label">Course Name</label>
            <select class="form-control" id="course" name="course" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course_name): ?>
                    <option value="<?= htmlspecialchars($course_name); ?>" <?= $course == $course_name ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($course_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="module_name" class="form-label">Module Name</label>
            <input type="text" class="form-control" id="module_name" name="module_name" value="<?= htmlspecialchars($module_name); ?>" required>
        </div>

        <div class="mb-3">
            <label for="module_code" class="form-label">Module Code</label>
            <input type="text" class="form-control" id="module_code" name="module_code" value="<?= htmlspecialchars($module_code); ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($date); ?>" required>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Duration</label>
            <input type="text" class="form-control" id="duration" name="duration" value="<?= htmlspecialchars($duration); ?>" required>
        </div>
        <div class="mb-3">
            <label for="num_assignments" class="form-label">Number of Assignments</label>
            <input type="text" class="form-control" id="num_assignments" name="num_assignments" value="<?= htmlspecialchars($num_assignments); ?>" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Module</button>
        <a href="modules.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
