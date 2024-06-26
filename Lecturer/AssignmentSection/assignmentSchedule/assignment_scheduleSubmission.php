<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Fetch data for dropdown menus
$courses = [];
$modules = [];
$batch_numbers = [];

// Fetch courses and batch numbers from login_tbl
$result = mysqli_query($conn, "SELECT DISTINCT course, batch_number FROM login_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
    $batch_numbers[] = $row['batch_number'];
}
$courses = array_unique($courses);
$batch_numbers = array_unique($batch_numbers);

// Fetch module names, codes, and courses from modules
$result = mysqli_query($conn, "SELECT module_name, module_code, course FROM modules");
while ($row = mysqli_fetch_assoc($result)) {
    $modules[] = $row;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $module_code = mysqli_real_escape_string($conn, $_POST['module_code']);
    $batch_number = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $assignment_name = mysqli_real_escape_string($conn, $_POST['assignment_name']);
    $date_of_issue = mysqli_real_escape_string($conn, $_POST['date_of_issue']);
    $date_of_submit = mysqli_real_escape_string($conn, $_POST['date_of_submit']);
    $view = mysqli_real_escape_string($conn, $_POST['view']);
    $allow_submission = isset($_POST['allow_submission']) ? 1 : 0;

    // Insert the data into the assignment_schedule table
    $sql = "INSERT INTO assignment_schedule (course, module_name, module_code, batch_number, assignment_name, date_of_issue, date_of_submit, view, allow_submission) VALUES ('$course', '$module_name', '$module_code', '$batch_number', '$assignment_name', '$date_of_issue', '$date_of_submit', '$view', '$allow_submission')";

    if (mysqli_query($conn, $sql)) {
        header("Location: assignment_schedule.php?message=insert");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all data from the assignment_schedule table
$assignment_schedule_data = [];
$result = mysqli_query($conn, "SELECT * FROM assignment_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $assignment_schedule_data[] = $row;
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-assignment_schedule.css">
    <link rel="stylesheet" href="viewprofile.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modules = <?php echo json_encode($modules); ?>;
            const courseSelect = document.getElementById('course');
            const moduleNameSelect = document.getElementById('module_name');
            const moduleCodeSelect = document.getElementById('module_code');

            courseSelect.addEventListener('change', function() {
                const selectedCourse = this.value;

                // Clear previous options
                moduleNameSelect.innerHTML = '<option value="">Select Module Name</option>';
                moduleCodeSelect.innerHTML = '<option value="">Select Module Code</option>';

                // Populate module dropdowns based on selected course
                modules.forEach(module => {
                    if (module.course === selectedCourse) {
                        const optionName = document.createElement('option');
                        optionName.value = module.module_name;
                        optionName.textContent = module.module_name;
                        moduleNameSelect.appendChild(optionName);
                    }
                });
            });

            moduleNameSelect.addEventListener('change', function() {
                const selectedModuleName = this.value;

                // Clear previous options
                moduleCodeSelect.innerHTML = '<option value="">Select Module Code</option>';

                // Populate module code based on selected module name
                modules.forEach(module => {
                    if (module.module_name === selectedModuleName) {
                        const optionCode = document.createElement('option');
                        optionCode.value = module.module_code;
                        optionCode.textContent = module.module_code;
                        moduleCodeSelect.appendChild(optionCode);
                    }
                });
            });

            // Search functionality
            const searchIcon = document.getElementById('search-icon');
            const searchInput = document.getElementById('search');
            const rows = document.querySelectorAll('#assignment-schedule-tbody tr');

            function filterRows() {
                const searchQuery = searchInput.value.toLowerCase().trim();
                rows.forEach(row => {
                    const batchNumberCell = row.querySelector('td:nth-child(1)');
                    if (batchNumberCell.textContent.toLowerCase().includes(searchQuery)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchIcon.addEventListener('click', filterRows);
            searchInput.addEventListener('input', filterRows);

            function manageAssignment(row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('modal').style.display = 'block';
                document.getElementById('manage-batch_number').value = cells[0].textContent;
                document.getElementById('manage-module_name').value = cells[1].textContent;
                document.getElementById('manage-module_code').value = cells[2].textContent;
                document.getElementById('manage-assignment_name').value = cells[3].textContent;
                document.getElementById('manage-date_of_issue').value = cells[4].textContent;
                document.getElementById('manage-date_of_submit').value = cells[5].textContent;
                document.getElementById('manage-view').checked = cells[6].textContent === 'Yes';
                document.getElementById('manage-status').value = cells[7].textContent;
                document.getElementById('manage-feedback').value = cells[8].textContent;
                document.getElementById('manage-mitigation_request').checked = cells[9].textContent === 'Yes';
                document.getElementById('manage-allow_submission').checked = cells[10].textContent === 'Yes';
            }

            // Attach the event listener to the buttons after the rows are created
            document.querySelectorAll('.manage-button').forEach(button => {
                button.addEventListener('click', function() {
                    manageAssignment(this.closest('tr'));
                });
            });

            function closeModal() {
                document.getElementById('modal').style.display = 'none';
            }

            document.querySelector('.close').addEventListener('click', closeModal);
        });
    </script>

</head>
<body>

    <?php if (isset($_GET['message'])): ?>
        <?php if ($_GET['message'] == 'insert'): ?>
            <div class="alert alert-success">Records are inserted successfully.</div>
        <?php elseif ($_GET['message'] == 'updated'): ?>
            <div class="alert alert-success">Records are updated successfully.</div>
        <?php elseif ($_GET['message'] == 'delete'): ?>
            <div class="alert alert-danger">Records are deleted successfully.</div>
        <?php endif; ?>
    <?php endif; ?>

    <form action="assignment_schedule.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="course">Course:</label>
                    <select id="course" name="course" required>
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="module_name">Module Name:</label>
                    <select id="module_name" name="module_name" required>
                        <option value="">Select Module Name</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="module_code">Module Code:</label>
                    <select id="module_code" name="module_code" required>
                        <option value="">Select Module Code</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="batch_number">Batch Number:</label>
                    <select id="batch_number" name="batch_number" required>
                        <option value="">Select Batch Number</option>
                        <?php foreach ($batch_numbers as $batch_number): ?>
                            <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="assignment_name">Assignment Name:</label>
                    <input type="text" id="assignment_name" name="assignment_name" required>
                </div>

                <div class="form-group">
                    <label for="date_of_issue">Date of Issue:</label>
                    <input type="date" id="date_of_issue" name="date_of_issue" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_submit">Date of Submit:</label>
                    <input type="date" id="date_of_submit" name="date_of_submit" required>
                </div>

                <div class="form-group">
                    <label for="view">View:</label>
                    <input type="checkbox" id="view" name="view" value="1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" id="status" name="status" required>
                </div>

                <div class="form-group">
                    <label for="feedback">Feedback:</label>
                    <input type="text" id="feedback" name="feedback">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="mitigation_request">Mitigation Request:</label>
                    <input type="checkbox" id="mitigation_request" name="mitigation_request" value="1">
                </div>

                <div class="form-group">
                    <label for="allow_submission">Allow Submission:</label>
                    <input type="checkbox" id="allow_submission" name="allow_submission" value="1">
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="view-link">Submit</button>
    </form>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>

            <h2>Edit Assignment Schedule</h2>

            <form action="assignment_scheduleUpdateDelete.php" method="POST">
                <input type="hidden" name="batch_number" id="manage-batch_number">

                <div class="form-group">
                    <label for="manage-module_name">Module Name:</label>
                    <input type="text" id="manage-module_name" name="module_name">
                </div>

                <div class="form-group">
                    <label for="manage-module_code">Module Code:</label>
                    <input type="text" id="manage-module_code" name="module_code">
                </div>

                <div class="form-group">
                    <label for="manage-assignment_name">Assignment Name:</label>
                    <input type="text" id="manage-assignment_name" name="assignment_name">
                </div>

                <div class="form-group">
                    <label for="manage-date_of_issue">Date of Issue:</label>
                    <input type="date" id="manage-date_of_issue" name="date_of_issue">
                </div>

                <div class="form-group">
                    <label for="manage-date_of_submit">Date of Submit:</label>
                    <input type="date" id="manage-date_of_submit" name="date_of_submit">
                </div>

                <div class="form-group">
                    <label for="manage-view">View:</label>
                    <input type="checkbox" id="manage-view" name="view" value="1" class="checkbox-label">
                    <br> <br>
                </div>

                <div class="form-group">
                    <label for="manage-status">Status:</label>
                    <input type="text" id="manage-status" name="status">
                </div>

                <div class="form-group">
                    <label for="manage-feedback">Feedback:</label>
                    <input type="text" id="manage-feedback" name="feedback">
                </div>

                <div class="form-group">
                    <label for="manage-mitigation_request">Mitigation Request:</label>
                    <input type="checkbox" id="manage-mitigation_request" name="mitigation_request" value="1" class="checkbox-label">
                </div>

                <div class="form-group">
                    <label for="manage-allow_submission">Allow Submission:</label>
                    <input type="checkbox" id="manage-allow_submission" name="allow_submission" value="1" class="checkbox-label">
                    <br> <br>
                </div>

                <div class="form-group">
                    <div class="button-container">
                        <button type="submit" name="action" value="edit" class="view-link">Edit</button>
                        <button type="submit" name="action" value="delete" class="delete-link">Delete</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <h2 class="topic">Assignment Schedule Details</h2>
    <br>

    <!-- Search bar -->
    <div class="search-bar">
        <label for="search">Search by Batch Number:</label>
        <input type="text" id="search" placeholder="Enter batch number">
        <button id="search-icon"><i class="fas fa-search"></i></button>
    </div>
    <br>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Batch Number</th>
                    <th>Module Name</th>
                    <th>Module Code</th>
                    <th>Assignment Name</th>
                    <th>Date of Issue</th>
                    <th>Date of Submit</th>
                    <th>View</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th>Mitigation Request</th>
                    <th>Allow Submission</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="assignment-schedule-tbody">
                <?php foreach ($assignment_schedule_data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['batch_number']) ?></td>
                        <td><?= htmlspecialchars($row['module_name']) ?></td>
                        <td><?= htmlspecialchars($row['module_code']) ?></td>
                        <td><?= htmlspecialchars($row['assignment_name']) ?></td>
                        <td><?= htmlspecialchars($row['date_of_issue']) ?></td>
                        <td><?= htmlspecialchars($row['date_of_submit']) ?></td>
                        <td><?= $row['view'] ? 'Yes' : 'No' ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['feedback']) ?></td>
                        <td><?= $row['mitigation_request'] ? 'Yes' : 'No' ?></td>
                        <td><?= $row['allow_submission'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <button class="manage-button">Manage</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
