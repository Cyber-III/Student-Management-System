<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM class_schedule WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$schedule = $result->fetch_assoc();
$stmt->close();

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

if (isset($_POST['update'])) {
    $course = $_POST['course'];
    $batch = $_POST['batch'];
    $module = $_POST['module'];
    $lecturer = $_POST['lecturer'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];
    $hall = $_POST['hall'];

    $sql = "UPDATE class_schedule SET course=?, batch=?, module=?, lecturer=?, date=?, time=?, notes=?, hall=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssssi', $course, $batch, $module, $lecturer, $date, $time, $notes, $hall, $id);
        if ($stmt->execute()) {
            $_SESSION['edit_success'] = "Class schedule updated successfully.";
            header("Location: modules.php");
            exit();
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
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <br><br>    
    <h1>Edit Class Schedule</h1>
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
                    <option value="<?= htmlspecialchars($course_name); ?>" <?= $schedule['course'] == $course_name ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($course_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="batch" class="form-label">Batch</label>
            <input type="text" class="form-control" id="batch" name="batch" value="<?= htmlspecialchars($schedule['batch']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="module" class="form-label">Module</label>
            <input type="text" class="form-control" id="module" name="module" value="<?= htmlspecialchars($schedule['module']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="lecturer" class="form-label">Lecturer</label>
            <input type="text" class="form-control" id="lecturer" name="lecturer" value="<?= htmlspecialchars($schedule['lecturer']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($schedule['date']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" class="form-control" id="time" name="time" value="<?= htmlspecialchars($schedule['time']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <input type="text" class="form-control" id="notes" name="notes" value="<?= htmlspecialchars($schedule['notes']); ?>">
        </div>
        <div class="mb-3">
            <label for="hall" class="form-label">Hall</label>
            <input type="text" class="form-control" id="hall" name="hall" value="<?= htmlspecialchars($schedule['hall']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="update">Update Schedule</button>
    </form>
</div>
</body>
</html>
