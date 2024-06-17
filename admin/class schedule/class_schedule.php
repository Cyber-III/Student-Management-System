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
                $_SESSION['delete_success'] = "Schedule deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error deleting schedule: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
    }
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch distinct courses, modules, and batches for filtering
$courses = [];
$modules = [];
$batches = [];
$result = mysqli_query($conn, "SELECT DISTINCT course FROM class_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
}
$result = mysqli_query($conn, "SELECT DISTINCT module FROM class_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $modules[] = $row['module'];
}
$result = mysqli_query($conn, "SELECT DISTINCT batch FROM class_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $batches[] = $row['batch'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule Table Page</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-class_schedule.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function searchSchedules() {
            var course = document.getElementById('course').value;
            var module = document.getElementById('module').value;
            var batch = document.getElementById('batch').value;
            var date = document.getElementById('date').value;

            // Prepare an object to store non-empty search parameters
            var searchData = {};
            if (course) searchData.course = course;
            if (module) searchData.module = module;
            if (batch) searchData.batch = batch;
            if (date) searchData.date = date;

            $.ajax({
                url: 'search_schedules.php',
                type: 'GET',
                data: searchData,
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
            <h1>Class Schedule</h1>
        </div>
        <div class="add-new my-3">
            <a href="add_schedule.php" class="btn btn-success">Add New Schedule</a>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="course" class="form-label">Search by Course:</label>
                <div class="input-group">
                    <select id="course" name="course" class="form-select">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label for="module" class="form-label">Search by Module:</label>
                <div class="input-group">
                    <select id="module" name="module" class="form-select">
                        <option value="">Select Module</option>
                        <?php foreach ($modules as $module): ?>
                            <option value="<?= htmlspecialchars($module) ?>"><?= htmlspecialchars($module) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label for="batch" class="form-label">Search by Batch:</label>
                <div class="input-group">
                    <select id="batch" name="batch" class="form-select">
                        <option value="">Select Batch</option>
                        <?php foreach ($batches as $batch): ?>
                            <option value="<?= htmlspecialchars($batch) ?>"><?= htmlspecialchars($batch) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Search by Date:</label>
                <div class="input-group">
                    <input type="date" id="date" name="date" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <button id="search-icon" class="btn btn-primary" onclick="searchSchedules()"><i class="bi bi-search"></i> Search</button>
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
                        <th>Hall</th>
                        <th>Notes</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody id="schedules-tbody">
                    <!-- Schedule data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
