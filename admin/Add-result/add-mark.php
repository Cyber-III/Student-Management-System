<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch module names (assuming this part remains unchanged)
$sql = "SELECT module_name FROM modules";
$result = mysqli_query($conn, $sql);

$module_names = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $module_names[] = $row['module_name'];
    }
}

// Get batch number from the URL parameter
if (isset($_GET['batch'])) {
    $batch = $_GET['batch'];
} else {
    // Handle error, redirect, or show error message
}

// Fetch student IDs and names from login_tbl for the selected batch
$sql = "SELECT username, student_name FROM login_tbl WHERE batch_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $batch);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[$row['username']] = $row['student_name']; // Associative array to store username => student_name
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
            <input type="text" id="course" name="course" value="<?php echo $_GET['course']; ?>" readonly>
            <br>
            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" value="<?php echo $batch; ?>" readonly>
            <br>
            <label for="semester">Semester:</label>
            <input type="text" id="semester" name="semester" value="<?php echo $_GET['semester']; ?>" readonly>
            <br>
            <label for="module">Module:</label>
            <select id="module" name="module" required>
                <option value="">-- Select Module --</option>
                <?php foreach ($module_names as $module_name): ?>
                    <option value="<?php echo $module_name; ?>"><?php echo $module_name; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="studentId">Student ID:</label>
            <select id="studentId" name="studentId" required onchange="updateStudentName()">
                <option value="">-- Select Student ID --</option>
                <?php foreach ($students as $username => $student_name): ?>
                    <option value="<?php echo $username; ?>"><?php echo $username; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="studentName">Student Name:</label>
            <input type="text" id="studentName" name="studentName" required readonly>
            <br>
            <div id="marksSection">
                <label for="assignmentMarks">Assignment Marks:</label>
                <input type="text" id="assignmentMarks" name="assignmentMarks" required>
                <br>
                <label for="presentationMarks">Presentation Marks:</label>
                <input type="text" id="presentationMarks" name="presentationMarks" required>
                <br>
                <label for="examMarks">Exam Marks:</label>
                <input type="text" id="examMarks" name="examMarks" required>
                <br>
                <label for="finalMarks">Final Marks:</label>
                <input type="text" id="finalMarks" name="finalMarks" required>
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

            populateModules(semester);
        });

        function updateStudentName() {
            const studentIdSelect = document.getElementById('studentId');
            const studentNameInput = document.getElementById('studentName');
            
            // Get the selected username
            const selectedUsername = studentIdSelect.value;
            
            // Lookup the student name in the students array
            const studentName = "<?php echo isset($students) ? addslashes(json_encode($students)) : '{}'; ?>";
            const students = JSON.parse(studentName);

            // Update the student name input field
            if (students[selectedUsername]) {
                studentNameInput.value = students[selectedUsername];
            } else {
                studentNameInput.value = '';
            }
        }

        function goBack() {
            // Reset the form values
            document.getElementById('studentName').value = '';
            document.getElementById('studentId').value = '';
            document.getElementById('assignmentMarks').value = '';
            document.getElementById('presentationMarks').value = '';
            document.getElementById('examMarks').value = '';
            document.getElementById('finalMarks').value = '';

            window.history.back(); // JavaScript function to navigate back
        }
    </script>
</body>
</html>
