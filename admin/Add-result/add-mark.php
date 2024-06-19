<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch module names (assuming this part remains unchanged)
$sql = "SELECT DISTINCT module_name FROM assignments";
$result = mysqli_query($conn, $sql);

$module_names = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $module_names[] = $row['module_name'];
    }
}

// Get batch number from the URL parameter
$batch = $_GET['batch'] ?? null;
if (!$batch) {
    // Handle error, redirect, or show error message
    exit('Batch number is missing.');
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
    <div class="header"></div>
    <div class="container">
        <div id="message" style="display:none;"></div> <!-- Message container -->
        <form id="addResultForm"> <!-- Removed action to handle via JS -->
            <div class="form-group">
                <label for="course">Course:</label>
                <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($_GET['course'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="batch">Batch:</label>
                <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($batch); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="semester">Semester:</label>
                <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($_GET['semester'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="module">Module:</label>
                <select id="module" name="module" required>
                    <option value="">-- Select Module --</option>
                    <?php foreach ($module_names as $module_name): ?>
                        <option value="<?php echo htmlspecialchars($module_name); ?>"><?php echo htmlspecialchars($module_name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="studentId">Student ID:</label>
                <select id="studentId" name="studentId" required onchange="updateStudentName()">
                    <option value="">-- Select Student ID --</option>
                    <?php foreach ($students as $username => $student_name): ?>
                        <option value="<?php echo htmlspecialchars($username); ?>"><?php echo htmlspecialchars($username); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="studentName">Student Name:</label>
                <input type="text" id="studentName" name="studentName" required readonly>
            </div>
            <div class="form-group">
                <label for="assignmentMarks">Assignment Marks:</label>
                <input type="text" id="assignmentMarks" name="assignmentMarks" min="0" max="100">
            </div>
            <div class="form-group">
                <label for="presentationMarks">Presentation Marks:</label>
                <input type="text" id="presentationMarks" name="presentationMarks" min="0" max="100">
            </div>
            <div class="form-group">
                <label for="examMarks">Exam Marks:</label>
                <input type="text" id="examMarks" name="examMarks" min="0" max="100">
            </div>
            <div class="form-group">
                <label for="finalMarks">Final Marks:</label>
                <input type="text" id="finalMarks" name="finalMarks" required min="0" max="100">
            </div>
            <div class="button-container">
                <button type="submit" class="submit-button">Submit Result</button>
                <button type="button" onclick="goBack()" class="back-button">Back</button>
            </div>
        </form>

        <!-- Add the assignments table here -->
        <div class="assignments-table">
            <h2>Coursework</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Batch Number</th>
                        <th>Module Name</th>
                        <th>Assignment Name</th>
                        <th>Submission Date</th>
                        <th>Document</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6">No assignments found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for displaying messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
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
        });

        function updateStudentName() {
            const studentIdSelect = document.getElementById('studentId');
            const studentNameInput = document.getElementById('studentName');
            
            const selectedUsername = studentIdSelect.value;
            
            const students = <?php echo json_encode($students); ?>;
            
            if (students[selectedUsername]) {
                studentNameInput.value = students[selectedUsername];
            } else {
                studentNameInput.value = '';
            }

            fetchAssignments(selectedUsername);
        }

        function fetchAssignments(username) {
            fetch('get_assignments.php?username=' + username)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('.assignments-table tbody');
                    tableBody.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(assignment => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${assignment.username}</td>
                                <td>${assignment.batch_number}</td>
                                <td>${assignment.module_name}</td>
                                <td>${assignment.assignment_name}</td>
                                <td>${assignment.submission_date}</td>
                                <td><a href="${assignment.file_path}" class="file-link" download>Download</a></td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="6">No assignments found.</td>';
                        tableBody.appendChild(row);
                    }
                })
                .catch(error => {
                    console.error('Error fetching assignments:', error);
                });
        }

        function goBack() {
            window.history.back();
        }

        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        function showMessage(message) {
            document.getElementById('modalMessage').innerText = message;
            document.getElementById('messageModal').style.display = 'block';
        }

        document.getElementById('addResultForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('process_result.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message);
            })
            .catch(error => {
                showMessage('An error occurred. Please try again.');
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
