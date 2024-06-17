<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch courses
$sql = "SELECT DISTINCT course FROM batches";
$result = mysqli_query($conn, $sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row['course'];
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
                    <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="batch">Select Batch:</label>
            <select name="batch" id="batch" required disabled>
                <option value="">-- Select Batch --</option>
                <!-- Batches will be populated based on selected course -->
            </select>
            <br>
            <label for="semester">Select Semester:</label>
            <select name="semester" id="semester" required>
                <option value="">-- Select Semester --</option>
            </select>
            <br>
            <br>
            <button type="submit">Submit</button> 
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

        courseSelect.addEventListener('change', function() {
            batchSelect.disabled = true;
            batchSelect.innerHTML = '<option value="">-- Select Batch --</option>'; // Clear previous options
            const selectedCourse = this.value;

            if (selectedCourse) {
                fetchBatches(selectedCourse);
            }
        });

        function fetchBatches(course) {
            fetch(`fetch_batches.php?course=${course}`)
                .then(response => response.json())
                .then(data => {
                    batchSelect.disabled = false;
                    data.forEach(batch => {
                        const option = document.createElement('option');
                        option.value = batch;
                        option.text = batch;
                        batchSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching batches:', error));
        }

        semesterSelect.addEventListener('change', function() {
            if (courseSelect.value && batchSelect.value && semesterSelect.value) {
                document.getElementById('selectCourseForm').submit();
            }
        });
    </script>
</body>
</html>
