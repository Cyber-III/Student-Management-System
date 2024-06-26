<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch courses
$result = mysqli_query($conn, "SELECT DISTINCT course_name FROM course_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course_name'];
}

// Fetch awarding batch numbers
$batch_numbers = [];
$result2 = mysqli_query($conn, "SELECT DISTINCT batch_no FROM batches");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['batch_no'];
}

// Fetch payment records
$payment_records = [];
$result3 = mysqli_query($conn, "SELECT * FROM graduation");
while ($row = mysqli_fetch_assoc($result3)) {
    $payment_records[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="plan.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.manage-button').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var batch_number = $(this).data('batch-number');
                var date = $(this).data('date');
                var time = $(this).data('time');
                var location = $(this).data('location');
                var course = $(this).data('course');

                $('#manageModal #id').val(id);
                $('#manageModal #name').val(name);
                $('#manageModal #batch_number').val(batch_number);
                $('#manageModal #date').val(date);
                $('#manageModal #time').val(time);
                $('#manageModal #location').val(location);
                $('#manageModal #course').val(course);
                $('#manageModal').show();
            });

            $('#editButton').click(function() {
                $('#manageForm').attr('action', 'update_grad.php');
                $('#manageForm').submit();
            });

            $('#deleteButton').click(function() {
                if (confirm('Are you sure you want to delete this record?')) {
                    $('#manageForm').attr('action', 'delete_grad.php');
                    $('#manageForm').submit();
                }
            });

            $('.close').click(function() {
                $('#manageModal').hide();
            });
        });

        function searchByBatch() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("batch_number_search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Start from index 1 to skip header row
                td = tr[i].getElementsByTagName("td")[1]; // Index 1 corresponds to batch_number column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function searchByUsername() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("username_search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Start from index 1 to skip header row
                td = tr[i].getElementsByTagName("td")[0]; // Index 0 corresponds to username column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</head>
<body>
    <div class="main_container">
    <?php if ($message == 'insertpayment'): ?>
        <div class="alert alert-success">Graduation Details were inserted successfully.</div>
    <?php elseif ($message == 'updatedpay'): ?>
        <div class="alert alert-success">Graduation Details were updated successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">Graduation Details were deleted successfully.</div>
    <?php endif; ?>
    
    <form action="paymentsubmission.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Graduation Title:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="batch_no">Batch Number:</label>
                    <select id="batch_no" name="batch_no" required>
                        <option value="">Select Batch Number</option>
                        <?php foreach ($batch_numbers as $batch_number): ?>
                            <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="description">Location:</label>
                    <input type="text" id="description" name="description" required>
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

            <br>
            <button type="submit" class="view-link">Submit</button>
        </div>
        <br>
        <br>
    </form>

    <!-- Search bar -->
    <div class="form-row">
        <div class="form-group">
            <label for="batch_number_search">Search by Batch Number:</label>
            <div class="input-group">
                <select id="batch_number_search" name="batch_number_search" onchange="searchByBatch()">
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="search-icon" class="search-button" onclick="searchByBatch()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="username_search">Search by Graduation Name:</label>
            <div class="input-group">
                <input type="text" id="username_search" name="username_search" placeholder="Graduation Name" onkeyup="searchByUsername()">
                <button type="button" id="search-icon" class="search-button" onclick="searchByUsername()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <br>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Batch Number</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Course</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($payment_records as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['name']) ?></td>
                <td><?= htmlspecialchars($record['batch_number']) ?></td>
                <td><?= htmlspecialchars($record['date']) ?></td>
                <td><?= htmlspecialchars($record['time']) ?></td>
                <td><?= htmlspecialchars($record['location']) ?></td>
                <td><?= htmlspecialchars($record['course']) ?></td>
                <td>
                    <button class="manage-button view-link"
                            data-id="<?= htmlspecialchars($record['id']) ?>"
                            data-name="<?= htmlspecialchars($record['name']) ?>"
                            data-batch-number="<?= htmlspecialchars($record['batch_number']) ?>"
                            data-date="<?= htmlspecialchars($record['date']) ?>"
                            data-time="<?= htmlspecialchars($record['time']) ?>"
                            data-location="<?= htmlspecialchars($record['location']) ?>"
                            data-course="<?= htmlspecialchars($record['course']) ?>">Manage</button>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <!-- The Modal -->
    <div id="manageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="manageForm" method="POST">
                <input type="hidden" id="id" name="id">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
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
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="time">Time:</label>
                            <input type="time" id="time" name="time" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location" required>
                        </div>

                        <div class="form-group">
                            <label for="course">Course:</label>
                            <input type="text" id="course" name="course" required>
                        </div>
                    </div>

                    <br>
                    <button type="button" id="editButton" class="view-link">Save</button>
                    <button type="button" id="deleteButton" class="delete-link">Delete</button>
                </div>
                <br>
                <br>
            </form>
        </div>
    </div>
</body>
</html>
