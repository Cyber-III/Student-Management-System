<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

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

// Fetch all data from the exam_schedule table
$exam_schedule_data = [];
$result = mysqli_query($conn, "SELECT * FROM exam_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $exam_schedule_data[] = $row;
}

// Display the success message if it exists
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Remove the message from the session after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-exam_schedule.css">
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
        });
    </script>
</head>
<body>
    <h1>Exam Schedule Form</h1>

    <?php if ($success_message): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form action="exam_scheduleSubmission.php" method="POST">
        <label for="course">Course:</label><br>
        <select id="course" name="course">
            <option value="">Select Course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="module_name">Module Name:</label><br>
        <select id="module_name" name="module_name">
            <option value="">Select Module Name</option>
            <!-- Options will be populated by JavaScript -->
        </select><br>

        <label for="module_code">Module Code:</label><br>
        <select id="module_code" name="module_code">
            <option value="">Select Module Code</option>
            <!-- Options will be populated by JavaScript -->
        </select><br>

        <label for="batch_number">Batch Number:</label><br>
        <select id="batch_number" name="batch_number">
            <option value="">Select Batch Number</option>
            <?php foreach ($batch_numbers as $batch_number): ?>
                <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="exam_name">Exam Name:</label><br>
        <input type="text" id="exam_name" name="exam_name"><br>

        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date"><br>

        <label for="time">Time:</label><br>
        <input type="time" id="time" name="time"><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location"><br>

        <label for="hours">Hours:</label><br>
        <input type="number" id="hours" name="hours"><br>

        <label for="allow_submission">Allow Submission:</label><br>
        <input type="checkbox" id="allow_submission" name="allow_submission" value="1"><br>

        <input type="submit" value="Submit">
    </form>

    
    <div class="table">
        <table>
            <thead>
                <tr>
                    <!-- <th>Course</th>
                    <th>Module Name</th> -->
                    <!-- <th>Module Code</th> -->
                    <th>Batch Number</th>
                    <th>Exam Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Hours</th>
                    <th>Allow Submission</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exam_schedule_data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['batch_number']) ?></td>
                        <td><?= htmlspecialchars($row['exam_name']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['hours']) ?></td>
                        <td><?= $row['allow_submission'] ? 'Yes' : 'No' ?></td>
                        <?php
                            echo '<td>
                                <form action="" method="POST">
                                    <button type="submit" class="view-link">MANAGE</button>
                                </form></td>';
                            echo "</tr>";
                        ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
