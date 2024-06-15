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
    <title>Campus Student Management System - Add Result</title>
    <link rel="stylesheet" href="add-mark.css">
    <link rel="stylesheet" href="../style-template.css">
</head>
<body>
    <div class="header">
        <h1>Add Student Result</h1>
    </div>
    <div class="container">
        <div id="message" style="display:none;"></div> <!-- Message container -->
        <form id="addResultForm" action="process_result.php" method="post"> <!-- Updated action -->
            <label for="course">Course:</label>
            <input type="text" id="course" name="course" readonly>
            <br>
            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" readonly>
            <br>
            <label for="semester">Semester:</label>
            <input type="text" id="semester" name="semester" readonly>
            <br>
            <label for="module">Module:</label>
            <select id="module" name="module" required>
                <!-- Options will be dynamically populated using JavaScript -->
            </select>
            <br>
            <label for="studentId">Student ID:</label>
            <input type="text" id="studentId" name="studentId" readonly>
            <br>
            <label for="studentName">Student Name:</label>
            <input type="text" id="studentName" name="studentName" required>
            <br>
            <div id="marksSection">
                <label for="marks">Marks:</label>
                <input type="text" id="marks" name="marks" required>
                <br>
            </div>
            <button type="submit">Submit Result</button>
        </form>
        <button onclick="goBack()" class="back-button">Back</button> <!-- Back button -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const course = params.get('course');
            const batch = params.get('batch');
            const semester = params.get('semester');

            document.getElementById('course').value = course;
            document.getElementById('batch').value = batch;
            document.getElementById('semester').value = semester;

            const studentId = generateStudentId(course, batch);
            document.getElementById('studentId').value = studentId;

            populateModules(semester);
        });

        function populateModules(semester) {
            const moduleSelect = document.getElementById('module');
            moduleSelect.innerHTML = ''; // Clear existing options

            switch (semester) {
                case 'Semester 1':
                    moduleSelect.innerHTML = `
                        <option value="s1m1">Architecture</option>
                        <option value="s1m2">Fundamental In Programming</option>
                        <option value="s1m3">System Analysis</option>
                        <option value="s1m4">Business Information System</option>
                    `;
                    break;
                case 'Semester 2':
                    moduleSelect.innerHTML = `
                        <option value="s2m1">Module 5</option>
                        <option value="s2m2">Module 6</option>
                        <option value="s2m3">Module 7</option>
                        <option value="s2m4">Module 8</option>
                    `;
                    break;
                case 'Semester 3':
                    moduleSelect.innerHTML = `
                        <option value="s3m1">Module 9</option>
                        <option value="s3m2">Module 10</option>
                        <option value="s3m3">Module 11</option>
                        <option value="s3m4">Module 12</option>
                    `;
                    break;
                case 'Semester 4':
                    moduleSelect.innerHTML = `
                        <option value="s4m1">Module 13</option>
                        <option value="s4m2">Module 14</option>
                        <option value="s4m3">Module 15</option>
                        <option value="s4m4">Module 16</option>
                    `;
                    break;
                default:
                    moduleSelect.innerHTML = ''; // Clear options if no specific logic
                    break;
            }
        }

        function generateStudentId(course, batch) {
            // Generating student ID based on the course and batch
            const courseCode = course.replace(/\s+/g, '').substring(0, 2).toUpperCase();
            const batchCode = batch.replace(/\s+/g, '').toUpperCase();
            return `${courseCode}${batchCode}`;
        }

        function goBack() {
            // Reset the form values
            document.getElementById('studentName').value = '';
            document.getElementById('studentId').value = '';
            document.getElementById('marks').value = '';

            window.history.back(); // JavaScript function to navigate back
        }
    </script>
</body>
</html>
