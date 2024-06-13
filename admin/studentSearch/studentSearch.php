<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch awarding batch numbers
$batch_numbers = [];
$result2 = mysqli_query($conn, "SELECT DISTINCT batch_no FROM batches");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['batch_no'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-studentSearch.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function searchByBatch() {
            var batchNumber = document.getElementById('batch_number').value;
            if (batchNumber !== "") {
                $.ajax({
                    url: 'search_student.php',
                    type: 'GET',
                    data: { batch_number: batchNumber },
                    success: function(response) {
                        document.getElementById('exam-schedule-tbody').innerHTML = response;
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        }

        function searchByUsername() {
            var username = document.getElementById('username').value;
            if (username !== "") {
                // Redirect to student_details.php with the username as a query parameter
                window.location.href = 'student_details.php?username=' + encodeURIComponent(username);
            }
        }
    </script>
</head>
<body>
    <!-- Search bar -->
    <div class="form-row">
        <div class="form-group">
            <label for="batch_number">Search by Batch Number:</label>
            <div class="input-group">
                <select id="batch_number" name="batch_number">
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="search-icon" onclick="searchByBatch()"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <div class="form-group">
            <label for="username">Search by Username:</label>
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Student's Username">
                <button id="search-icon" onclick="searchByUsername()"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
    <br>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>ST_Name</th>
                    <th>Username</th>
                    <th>Course</th>
                    <th>Batch_No</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>NIC</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody id="exam-schedule-tbody">
                
            </tbody>
        </table>
    </div>
</body>
</html>
