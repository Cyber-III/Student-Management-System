<?php
session_start();
ob_start();  // Start output buffering

include_once('../connection.php');  // Adjust path as necessary

include_once('../../admin/assests/content/static/template.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: class_schedule.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM class_schedule WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();

    if (!$schedule) {
        header("Location: class_schedule.php");
        exit();
    }
} else {
    die("Error in SQL query: " . $conn->error);
}

// Fetch lecturers from lecturers table for the dropdown menu
$lecturers = [];
$sql_lecturers = "SELECT name FROM lecturers";
$result_lecturers = $conn->query($sql_lecturers);
if ($result_lecturers) {
    while ($row = $result_lecturers->fetch_assoc()) {
        $lecturers[] = $row['name'];
    }
} else {
    $error = "Error fetching lecturers: " . $conn->error;
}

// Fetch batches from batches table for the dropdown menu
$batches = [];
$sql_batches = "SELECT batch_no FROM batches";
$result_batches = $conn->query($sql_batches);
if ($result_batches) {
    while ($row = $result_batches->fetch_assoc()) {
        $batches[] = $row['batch_no'];
    }
} else {
    $error = "Error fetching batches: " . $conn->error;
}

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

if (isset($_POST['update'])) {
    $course = $_POST['course'];
    $batch = $_POST['batch'];
    $module = $_POST['module'];
    $lecturer = $_POST['lecturer'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $hall = $_POST['hall'];
    $notes = $_POST['notes'];

    $sql = "UPDATE class_schedule SET course = ?, batch = ?, module = ?, lecturer = ?, date = ?, time = ?, hall = ?, notes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssssi', $course, $batch, $module, $lecturer, $date, $time, $hall, $notes, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['edit_success'] = "Class schedule edited successfully.";
        header("Location: class_schedule.php");
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
    <title>Edit Class Schedule</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-class_schedule.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
<div class="container_main">
    <br><br>
    <h1>Edit Class Schedule</h1>
    <form method="post">
        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
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
            <select class="form-control" id="batch" name="batch" required>
                <option value="">Select Batch</option>
                <?php foreach ($batches as $batch_no): ?>
                    <option value="<?= htmlspecialchars($batch_no); ?>" <?= $schedule['batch'] == $batch_no ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($batch_no); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="module" class="form-label">Module</label>
            <select class="form-control" id="module" name="module" required>
                <option value="">Select Module</option>
                <!-- Options will be populated by JavaScript -->
            </select>
        </div>
        <div class="mb-3">
            <label for="lecturer" class="form-label">Lecturer</label>
            <select class="form-control" id="lecturer" name="lecturer" required>
                <option value="">Select Lecturer</option>
                <?php foreach ($lecturers as $lecturer_name): ?>
                    <option value="<?= htmlspecialchars($lecturer_name); ?>" <?= $schedule['lecturer'] == $lecturer_name ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($lecturer_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
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
            <label for="hall" class="form-label">Hall</label>
            <input type="text" class="form-control" id="hall" name="hall" value="<?= htmlspecialchars($schedule['hall']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes"><?= htmlspecialchars($schedule['notes']); ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="class_schedule.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var courseSelect = document.getElementById('course');
    var moduleSelect = document.getElementById('module');
    var currentModule = <?= json_encode($schedule['module']); ?>;

    courseSelect.addEventListener('change', function() {
        var course = this.value;

        // Clear existing options
        moduleSelect.innerHTML = '<option value="">Select Module</option>';

        if (course) {
            // Fetch modules for the selected course using AJAX
            fetch('get_modules.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'course=' + encodeURIComponent(course)
            })
            .then(response => response.json())
            .then(modules => {
                modules.forEach(function(module) {
                    var option = document.createElement('option');
                    option.value = module;
                    option.textContent = module;
                    if (module === currentModule) {
                        option.selected = true;
                    }
                    moduleSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching modules:', error));
        }
    });

    // Trigger change event initially if course is pre-selected (edit mode)
    if (courseSelect.value) {
        courseSelect.dispatchEvent(new Event('change'));
    }
});
</script>


</html>
