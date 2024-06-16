<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM modules WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $_SESSION['delete_success'] = "Module deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error deleting module: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
    }
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch distinct courses and module names for filtering
$courses = [];
$module_names = [];
$result = mysqli_query($conn, "SELECT DISTINCT course FROM modules");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
}
$result = mysqli_query($conn, "SELECT DISTINCT module_name FROM modules");
while ($row = mysqli_fetch_assoc($result)) {
    $module_names[] = $row['module_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules Table Page</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-modules.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function searchModules() {
            var course = document.getElementById('course').value;
            var moduleName = document.getElementById('module_name').value;
            $.ajax({
                url: 'search_modules.php',
                type: 'GET',
                data: { course: course, module_name: moduleName },
                success: function(response) {
                    document.getElementById('modules-tbody').innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
</head>
<body class="body">
    <div class="container">
        <div class="topic">
            <br><br>
            <h1>Modules</h1>
        </div>
        <div class="add-new">
            <br>
            <a href="add_module.php" class="btn btn-success">Add New Module</a>
        </div>
        <br><br>
        <div class="form-row">
            <div class="form-group">
                <label for="course">Search by Course:</label>
                <div class="input-group">
                    <select id="course" name="course">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="search-icon" onclick="searchModules()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label for="module_name">Search by Module Name:</label>
                <div class="input-group">
                    <select id="module_name" name="module_name">
                        <option value="">Select Module Name</option>
                        <?php foreach ($module_names as $module_name): ?>
                            <option value="<?= htmlspecialchars($module_name) ?>"><?= htmlspecialchars($module_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="search-icon" onclick="searchModules()"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <br>
        <div class="table-container">
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Module Name</th>
                        <th>Module Code</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Number of Assignments</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody id="modules-tbody">
                    <!-- Modules data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
