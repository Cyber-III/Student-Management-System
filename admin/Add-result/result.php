
<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

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
            <h1>Add Student Result (Admin)</h1>
        </div>
        <form id="selectCourseForm" action="add-mark.php" method="get"> <!-- Use GET method to pass parameters -->
            <label for="course">Select Course:</label>
            <select name="course" id="course" required>
                <option value="">-- Select Course --</option>
                <option value="Software Engineering">HD in Software Engineering</option>
                <option value="Computer Science">Computer Science</option>
                <option value="BSc Software Engineering">BSc Software Engineering</option>
                <!-- Add more courses as needed -->
            </select>
            <br>
            <label for="batch">Select Batch:</label>
            <select name="batch" id="batch" disabled required>
                <option value="">-- Select Batch --</option>
            </select>
            <br>
            <label for="semester">Select Semester:</label>
            <select name="semester" id="semester" disabled required>
                <option value="">-- Select Semester --</option>
            </select>
            <br>
 
            <br>
            <button type="submit">Proceed</button> <!-- Submit form to add-mark.php -->
        </form>
    </div>
    <script>
        const courseSelect = document.getElementById('course');
        const semesterSelect = document.getElementById('semester');
        const batchSelect = document.getElementById('batch');

        const data = {
            'Software Engineering': {
                semesters: ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4'],
                batches: ['Batch 1', 'Batch 2', 'Batch 3', 'Batch 4', 'Batch 5', 'Batch 6']
            },
            'Computer Science': {
                semesters: ['Semester A', 'Semester B', 'Semester C'],
                batches: ['Batch A1', 'Batch A2', 'Batch A3', 'Batch A4', 'Batch B1', 'Batch B2']
            },
            'BSc Software Engineering': {
                semesters: ['Semester 0', 'Semester 9', 'Semester 7'],
                batches: ['Batch 0A', 'Batch 0B', 'Batch 0C', 'Batch 0D', 'Batch 9A1', 'Batch 9A2']
            }
        };

        courseSelect.addEventListener('change', function() {
            const selectedCourse = this.value;
            semesterSelect.disabled = false;
            batchSelect.disabled = false;
            semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>'; // Clear previous options
            batchSelect.innerHTML = '<option value="">-- Select Batch --</option>'; // Clear previous options

            if (selectedCourse) {
                const availableSemesters = data[selectedCourse].semesters;
                const availableBatches = data[selectedCourse].batches;
                availableSemesters.forEach(semester => {
                    const option = document.createElement('option');
                    option.value = semester;
                    option.text = semester;
                    semesterSelect.appendChild(option);
                });
                availableBatches.forEach(batch => {
                    const option = document.createElement('option');
                    option.value = batch;
                    option.text = batch;
                    batchSelect.appendChild(option);
                });
            } else {
                semesterSelect.disabled = true;
                batchSelect.disabled = true;
            }
        });

        semesterSelect.addEventListener('change', function() {
            const selectedCourse = courseSelect.value;
            const selectedSemester = this.value;
            if (selectedCourse && selectedSemester) {
                // Enable submit button or navigate to add-mark.php automatically
                // based on your application's flow
                document.getElementById('selectCourseForm').submit();
            }
        });
    </script>
</body>
</html>
