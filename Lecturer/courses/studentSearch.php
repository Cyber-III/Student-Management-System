<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Initialize sets
$courses = [];
$batch_numbers = [];
$award_unis = [];

// Fetch courses
$result = mysqli_query($conn, "SELECT DISTINCT course_name FROM course_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course_name'];
}

// Fetch awarding universities
$result1 = mysqli_query($conn, "SELECT DISTINCT uni_name FROM awarding_uni");
while ($row = mysqli_fetch_assoc($result1)) {
    $award_unis[] = $row['uni_name'];
}

// Fetch awarding batch numbers
$result2 = mysqli_query($conn, "SELECT DISTINCT batch_no FROM batches");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['batch_no'];
}

// Fetch all data from the exam_schedule table
$exam_schedule_data = [];
$result = mysqli_query($conn, "SELECT * FROM batches");
while ($row = mysqli_fetch_assoc($result)) {
    $exam_schedule_data[] = $row;
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-studentSearch.css">
</head>

<body>
<div class="main_container">
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">The batch was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The batch was deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success">The batch was updated successfully.</div>
    <?php elseif ($message == 'exists'): ?>
        <div class="alert alert-danger">The batch already exists.</div>
    <?php endif; ?>

    <br>
    
    <h2>Add New Batch</h2>
    <form action="batch_createdelete.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="batch_no">Batch Number:</label>
                    <input type="text" id="batch_no" name="batch_no" required>
                </div>

                <div class="form-group">
                    <label for="batch_course">Course:</label>
                    <select id="batch_course" name="batch_course" required>
                        <option value="">Select Course</option>
                        <?php foreach (array_unique($courses) as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="batch_uni">Awarding University:</label>
                    <select id="batch_uni" name="batch_uni" required>
                        <option value="">Select University</option>
                        <?php foreach ($award_unis as $award_uni): ?>
                            <option value="<?= htmlspecialchars($award_uni) ?>"><?= htmlspecialchars($award_uni) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="intake">Intake:</label>
                    <input type="text" id="intake" name="intake" required>
                </div>
                
            </div>


        </div>
        <br>
        <button type="submit" class="batch view-link">Add Batch</button>
    </form>

    <!-- Search bar -->
    <div class="search-bar">
        <label for="search">Search by Course:</label>
        <select id="search" class="search-select">
            <option value="">Select Course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
            <?php endforeach; ?>
        </select>
        <button id="search-icon"><i class="fas fa-search"></i></button>
    </div>
    <br>

    <!-- Table -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Batch Number</th>
                    <th>Course</th>
                    <th>Intake</th>
                    <th>Commencement Date</th>
                    <th>Awarding University</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="exam-schedule-tbody">
                <?php foreach ($exam_schedule_data as $row): ?>
                    <tr>
                        <td data-cell="Batch Number"><?= htmlspecialchars($row['batch_no']) ?></td>
                        <td data-cell="Course"><?= htmlspecialchars($row['course']) ?></td>
                        <td data-cell="Intake"><?= htmlspecialchars($row['intake']) ?></td>
                        <td data-cell="Commencement Date"><?= htmlspecialchars($row['commencement_date']) ?></td>
                        <td data-cell="Awarding University"><?= htmlspecialchars($row['award_uni']) ?></td>
                        <td data-cell="Action"><button onclick="manageExam(this)" class="manage-button view-link">Manage</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- manage modal -->
    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            <h2>Manage Batch</h2>
            <form id="manageBatchForm" action="batch_manage.php" method="POST">
                <input type="hidden" id="manage_batch_no" name="batch_no">
                <div class="form-group">
                    <label for="manage_batch_course">Course:</label>
                    <select id="manage_batch_course" name="course" required>
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="manage_batch_intake">Intake:</label>
                    <input type="text" id="manage_batch_intake" name="intake" required>
                </div>
                <div class="form-group">
                    <label for="manage_batch_cdate">Commencement Date:</label>
                    <input type="date" id="manage_batch_cdate" name="commencement_date" required>
                </div>
                <div class="form-group">
                    <label for="manage_batch_uni">Awarding University:</label>
                    <select id="manage_batch_uni" name="award_uni" required>
                        <option value="">Select University</option>
                        <?php foreach ($award_unis as $award_uni): ?>
                            <option value="<?= htmlspecialchars($award_uni) ?>"><?= htmlspecialchars($award_uni) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Save</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>

</div>

</body>

<script>
    var manageBatchModal = document.getElementById("manageBatchModal");
    var closeManageBatchModal = document.getElementById("closeManageBatchModal");

    // close button
    closeManageBatchModal.onclick = function() {
        manageBatchModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modals, close them
    window.onclick = function(event) {
        if (event.target == manageBatchModal) {
            manageBatchModal.style.display = "none";
        }
    }

    // Manage Exam functionality
    function manageExam(button) {
        var row = button.parentElement.parentElement;
        var batchNo = row.querySelector("[data-cell='Batch Number']").innerText;
        var course = row.querySelector("[data-cell='Course']").innerText;
        var intake = row.querySelector("[data-cell='Intake']").innerText;
        var cdate = row.querySelector("[data-cell='Commencement Date']").innerText;
        var uni = row.querySelector("[data-cell='Awarding University']").innerText;

        document.getElementById("manage_batch_no").value = batchNo;
        document.getElementById("manage_batch_course").value = course;
        document.getElementById("manage_batch_intake").value = intake;
        document.getElementById("manage_batch_cdate").value = cdate;
        document.getElementById("manage_batch_uni").value = uni;

        manageBatchModal.style.display = "block";
    }

    document.getElementById("manageBatchForm").addEventListener("submit", function(event) {
        var action = document.activeElement.value;
        if (action === 'delete') {
            var confirmDelete = confirm("Are you sure to delete this batch?");
            if (!confirmDelete) {
                event.preventDefault();
            }
        }
    });

    // Search functionality
    document.getElementById("search-icon").addEventListener("click", function() {
        var searchValue = document.getElementById("search").value.toLowerCase();
        var tableRows = document.querySelectorAll("#exam-schedule-tbody tr");

        tableRows.forEach(function(row) {
            var courseCell = row.querySelector("[data-cell='Course']").innerText.toLowerCase();
            if (searchValue === "" || courseCell.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>



</html>
