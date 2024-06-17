<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM class_schedule WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $_SESSION['delete_success'] = "Class schedule deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error deleting class schedule: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
    }
}

// Fetch distinct courses and modules for filtering
$courses = [];
$modules = [];
$result = mysqli_query($conn, "SELECT DISTINCT course FROM class_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
}
$result = mysqli_query($conn, "SELECT DISTINCT module FROM class_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $modules[] = $row['module'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule Table Page</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function searchSchedules() {
            var course = document.getElementById('course').value;
            var module = document.getElementById('module').value;

            $.ajax({
                url: 'search_schedules.php',
                type: 'GET',
                data: { course: course, module: module },
                success: function(response) {
                    document.getElementById('schedules-tbody').innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="topic">
            <h1>Class Schedules</h1>
        </div>
        <div class="add-new my-3">
            <a href="add_schedule.php" class="btn btn-success">Add New Schedule</a>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="course" class="form-label">Search by Course:</label>
                <div class="input-group">
                    <select id="course" name="course" class="form-select">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="search-icon" class="btn btn-outline-primary" onclick="searchSchedules()"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-6">
                <label for="module" class="form-label">Search by Module:</label>
                <div class="input-group">
                    <select id="module" name="module" class="form-select">
                        <option value="">Select Module</option>
                        <?php foreach ($modules as $module): ?>
                            <option value="<?= htmlspecialchars($module) ?>"><?= htmlspecialchars($module) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="search-icon" class="btn btn-outline-primary" onclick="searchSchedules()"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['delete_success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['delete_success']); ?>
                </div>
                <?php unset($_SESSION['delete_success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['edit_success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['edit_success']); ?>
                </div>
                <?php unset($_SESSION['edit_success']); ?>
            <?php endif; ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>Module</th>
                        <th>Lecturer</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Notes</th>
                        <th>Hall</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody id="schedules-tbody">
                    <!-- Class schedule data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
