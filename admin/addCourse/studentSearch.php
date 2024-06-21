<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Initialize sets
$courses = [];
$award_unis = [];

// Fetch courses
$result = mysqli_query($conn, "SELECT DISTINCT course_name FROM course_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course_name'];
}

// Fetch awarding universities
$result1 = mysqli_query($conn, "SELECT DISTINCT award_uni FROM course_tbl");
while ($row = mysqli_fetch_assoc($result1)) {
    $award_unis[] = $row['award_uni'];
}

// Fetch all data from the course_tbl table
$course_data = [];
$result = mysqli_query($conn, "SELECT * FROM course_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $course_data[] = $row;
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

    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">The course was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The course was deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success">The course was updated successfully.</div>
    <?php endif; ?>

    <br>
    
    <h2>Add New Course</h2>
    <form action="batch_createdelete.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="course_name">Course:</label>
                    <input type="text" id="course_name" name="course_name" required>
                </div>

                <div class="form-group">
                    <label for="award_uni">Awarding University:</label>
                    <select id="award_uni" name="award_uni" required>
                        <option value="">Select University</option>
                        <?php foreach ($award_unis as $award_uni): ?>
                            <option value="<?= htmlspecialchars($award_uni) ?>"><?= htmlspecialchars($award_uni) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Course</button>
    </form>

    <div class="searchbars">
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

        <!-- Search bar for University -->
        <div class="search-bar">
            <label for="search-uni">Search by University:</label>
            <select id="search-uni" class="search-select">
                <option value="">Select University</option>
                <?php foreach ($award_unis as $award_uni): ?>
                    <option value="<?= htmlspecialchars($award_uni) ?>"><?= htmlspecialchars($award_uni) ?></option>
                <?php endforeach; ?>
            </select>
            <button id="search-uni-icon"><i class="fas fa-search"></i></button>
        </div>

        <br>

    </div>
    

    <!-- Table -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Awarding University</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="course-tbody">
                <?php foreach ($course_data as $row): ?>
                    <tr>
                        <td data-cell="Course"><?= htmlspecialchars($row['course_name']) ?></td>
                        <td data-cell="Awarding University"><?= htmlspecialchars($row['award_uni']) ?></td>
                        <td data-cell="Action">
                            <button class="manage-button view-link" data-course-id="<?= htmlspecialchars($row['course_id']) ?>" data-course-name="<?= htmlspecialchars($row['course_name']) ?>" data-award-uni="<?= htmlspecialchars($row['award_uni']) ?>">Manage</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- manage modal -->
    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            <h2>Manage Course</h2>
            <form id="manageBatchForm" action="batch_manage.php" method="POST">
                <input type="hidden" id="manage_course_id" name="course_id">
                <div class="form-group">
                    <label for="manage_course_name">Course:</label>
                    <input type="text" id="manage_course_name" name="course_name" required>
                </div>
                <div class="form-group">
                    <label for="manage_award_uni">Awarding University:</label>
                    <select id="manage_award_uni" name="award_uni" required>
                        <option value="">Select University</option>
                        <?php foreach ($award_unis as $award_uni): ?>
                            <option value="<?= htmlspecialchars($award_uni) ?>"><?= htmlspecialchars($award_uni) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>

    <script>
    var manageBatchModal = document.getElementById("manageBatchModal");
    var closeManageBatchModal = document.getElementById("closeManageBatchModal");

    // Close button
    closeManageBatchModal.onclick = function() {
        manageBatchModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modals, close them
    window.onclick = function(event) {
        if (event.target == manageBatchModal) {
            manageBatchModal.style.display = "none";
        }
    }

    // Manage Course functionality
    document.querySelectorAll('.manage-button').forEach(button => {
        button.addEventListener('click', function() {
            var courseId = this.dataset.courseId;
            var courseName = this.dataset.courseName;
            var awardUni = this.dataset.awardUni;

            document.getElementById('manage_course_id').value = courseId;
            document.getElementById('manage_course_name').value = courseName;
            document.getElementById('manage_award_uni').value = awardUni;

            manageBatchModal.style.display = "block";
        });
    });

    document.getElementById("manageBatchForm").addEventListener("submit", function(event) {
        var action = document.activeElement.value;
        if (action === 'delete') {
            var confirmDelete = confirm("Are you sure to delete this course?");
            if (!confirmDelete) {
                event.preventDefault();
            }
        }
    });

    // Search functionality for Course
    document.getElementById("search-icon").addEventListener("click", function() {
        var searchValue = document.getElementById("search").value.toLowerCase();
        var tableRows = document.querySelectorAll("#course-tbody tr");

        tableRows.forEach(function(row) {
            var courseCell = row.querySelector("[data-cell='Course']").innerText.toLowerCase();
            if (searchValue === "" || courseCell.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Search functionality for University
    document.getElementById("search-uni-icon").addEventListener("click", function() {
        var searchValue = document.getElementById("search-uni").value.toLowerCase();
        var tableRows = document.querySelectorAll("#course-tbody tr");

        tableRows.forEach(function(row) {
            var uniCell = row.querySelector("[data-cell='Awarding University']").innerText.toLowerCase();
            if (searchValue === "" || uniCell.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>


</body>
</html>
