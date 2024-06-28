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

// Initialize the success message
$success_message = "";

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
        $stmt->bind_param('ssssss', $module_name, $module_code, $topic, $batch_number, $course, $download);
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
    <link rel="stylesheet" href="style-course_materials.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="body">
<div class="container">
    <h1>Add New Course Material</h1>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <select class="form-control" id="course" name="course" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="module_name" class="form-label">Module Name</label>
            <select class="form-control" id="module_name" name="module_name" required>
                <option value="">Select Module</option>
                <!-- Options will be populated by JavaScript -->
            </select>
        </div>
        <div class="mb-3">
            <label for="module_code" class="form-label">Module Code</label>
            <input type="text" class="form-control" id="module_code" name="module_code" readonly required>
        </div>
        <div class="mb-3">
            <label for="batch_number" class="form-label">Batch Number</label>
            <select class="form-control" id="batch_number" name="batch_number" required>
                <option value="">Select Batch</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?php echo htmlspecialchars($batch); ?>"><?php echo htmlspecialchars($batch); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="topic" class="form-label">Topic</label>
            <input type="text" class="form-control" id="topic" name="topic" required>
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

<script>
    $(document).ready(function(){
        $('#course').change(function(){
            var course = $(this).val();
            if (course) {
                $.ajax({
                    url: 'get_modules.php',
                    type: 'POST',
                    data: {course: course},
                    success: function(response) {
                        $('#module_name').html('<option value="">Select Module</option>' + response);
                        $('#module_code').val('');
                    }
                });
            } else {
                $('#module_name').html('<option value="">Select Module</option>');
                $('#module_code').val('');
            }
        });

        $('#module_name').change(function(){
            var module_code = $(this).find(':selected').data('code');
            $('#module_code').val(module_code);
        });
    });
</script>
</body>
</html>
