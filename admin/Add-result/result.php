<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch courses
$sql = "SELECT course_name FROM course_tbl";
$result = mysqli_query($conn, $sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row['course_name'];
    }
}

// Fetch batches
$sql = "SELECT batch_no FROM batches";
$result = mysqli_query($conn, $sql);

$batches = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $batches[] = $row['batch_no'];
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Student Management System - Select Course</title>
    <!--Template Css-->
    <link rel="stylesheet" href="../style-template.css">

    <!--Result Css-->
    <link rel="stylesheet" href="result.css">
</head>
<body>
    <div class="container">
        <div class="topic">
            <h1>Add Student Result</h1>
        </div>
        <form id="selectCourseForm" action="add-mark.php" method="get"> 
            <label for="course">Select Course:</label>
            <select name="course" id="course" required>
                <option value="">-- Select Course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course; ?>"><?php echo $course; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="batch">Select Batch:</label>
            <select name="batch" id="batch" required>
                <option value="">-- Select Batch --</option>
                <?php foreach ($batches as $batch_no): ?>
                    <option value="<?php echo $batch_no; ?>"><?php echo $batch_no; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="semester">Select Semester:</label>
            <select name="semester" id="semester" required>
                <option value="">-- Select Semester --</option>
            </select>
            <br>
 
            <br>
            <button type="submit">Proceed</button> 
        </form>
    </div>
    <script>
        const courseSelect = document.getElementById('course');
        const semesterSelect = document.getElementById('semester');
        const batchSelect = document.getElementById('batch');

        const semesters = [
            'Semester 1', 'Semester 2', 'Semester 3', 'Semester 4',
            'Semester 5', 'Semester 6', 'Semester 7', 'Semester 8'
        ];

        
        function populateSemesters() {
            semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>'; // Clear previous options
            semesters.forEach(semester => {
                const option = document.createElement('option');
                option.value = semester;
                option.text = semester;
                semesterSelect.appendChild(option);
            });
        }

        
        populateSemesters();

        // Enable the semester dropdown when a course is selected
        courseSelect.addEventListener('change', function() {
            semesterSelect.disabled = false;
            batchSelect.disabled = false;
        });

        // Enable the submit button when a semester is selected
        semesterSelect.addEventListener('change', function() {
            if (courseSelect.value && batchSelect.value && semesterSelect.value) {
                document.getElementById('selectCourseForm').submit();
            }
        });
    </script>
</body>
</html>
