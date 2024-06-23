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
$course = $batch = $module = $lecturer = $date = $time = $hall = $notes = "";
$success_message = $error = "";

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

// Fetch courses from courses table for the dropdown menu
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

// Handle form submission for adding a new class schedule
if (isset($_POST['add'])) {
    // Sanitize and validate inputs (assuming input validation has been done)
    $course = $_POST['course'];
    $batch = $_POST['batch'];
    $module = $_POST['module'];
    $lecturer = $_POST['lecturer'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $hall = $_POST['hall'];
    $notes = $_POST['notes'];

    // Prepare and execute SQL insertion
    $sql = "INSERT INTO class_schedule (course, batch, module, lecturer, date, time, hall, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssss', $course, $batch, $module, $lecturer, $date, $time, $hall, $notes);
        if ($stmt->execute()) {
            $success_message = "Class schedule added successfully.";
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
    <title>Add Class Schedule</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-class_schedule.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="body">
<div class="container_main">
    <br><br>    
    <h1>Add Class Schedule</h1>
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
            <label for="batch" class="form-label">Batch</label>
            <select class="form-control" id="batch" name="batch" required>
                <option value="">Select Batch</option>
                <?php foreach ($batches as $batch_no): ?>
                    <option value="<?= htmlspecialchars($batch_no); ?>" <?= $batch == $batch_no ? 'selected' : ''; ?>>
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
                    <option value="<?= htmlspecialchars($lecturer_name); ?>" <?= $lecturer == $lecturer_name ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($lecturer_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($date); ?>" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" class="form-control" id="time" name="time" value="<?= htmlspecialchars($time); ?>" required>
        </div>
        <div class="mb-3">
            <label for="hall" class="form-label">Hall</label>
            <input type="text" class="form-control" id="hall" name="hall" value="<?= htmlspecialchars($hall); ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes"><?= htmlspecialchars($notes); ?></textarea>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Class Schedule</button>
        <a href="class_schedule.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<script>
document.getElementById('course').addEventListener('change', function() {
    var course = this.value;
    var moduleSelect = document.getElementById('module');

    // Clear existing options
    moduleSelect.innerHTML = '<option value="">Select Module</option>';

    if (course) {
        // Fetch modules for the selected course
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
                moduleSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>
</body>
</html>
