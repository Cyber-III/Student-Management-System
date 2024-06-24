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

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('password-error');
            const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

            if (!passwordPattern.test(password)) {
                errorMessage.textContent = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.";
                return false;
            }

            errorMessage.textContent = "";
            return true;
        }

        function validateForm(event) {
            if (!validatePassword()) {
                event.preventDefault();
            }
        }
    </script>
</head>

<body>
    <div class="main_container">
        <?php if ($message == 'insertstudent'): ?>
            <div class="alert alert-success">Student was inserted successfully.</div>
        <?php elseif ($message == 'insert'): ?>
            <div class="alert alert-success">The batch was created successfully.</div>
        <?php elseif ($message == 'delete'): ?>
            <div class="alert alert-danger">The batch was deleted successfully.</div>
        <?php endif; ?>

        <form action="studentSubmission.php" method="POST" onsubmit="validateForm(event)">
            <div class="form-container">

                <div class="form-row">
                    <div class="form-group">
                        <label for="sid">Student ID: </label>
                        <input type="text" id="sid" name="sid" required>
                    </div>

                    <div class="form-group">
                        <label for="sname">Student Name: </label>
                        <input type="text" id="sname" name="sname" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password: </label>
                        <input type="password" id="password" name="password" required oninput="validatePassword()">
                        <span id="password-error" class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="course">Course:</label>
                        <select id="course" name="course" required>
                            <option value="">Select Course</option>
                            <?php foreach (array_unique($courses) as $course): ?>
                                <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="batch_number">Batch Number:</label>
                        <select id="batch_number" name="batch_number" required>
                            <option value="">Select Batch Number</option>
                            <?php foreach ($batch_numbers as $batch_number): ?>
                                <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bdate">Date of Birth:</label>
                        <input type="date" id="bdate" name="bdate" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nic">National Identity Card Number:</label>
                        <input type="text" id="nic" name="nic" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact">Contact Number:</label>
                        <input type="text" id="contact" name="contact" required>
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="uni_num">University Number:</label>
                        <input type="text" id="uni_num" name="uni_num" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Local Branch:</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <input type="radio" id="male" name="gender" value="Male" required> Male
                        <input type="radio" id="female" name="gender" value="Female" required> Female
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="view-link">Submit</button>
        </form>
        <br>
    </div>


</body>

<script>
    // Get modal elements for New Batch
    var newBatchModal = document.getElementById("newBatchModal");
    var newBatchBtn = document.getElementById("newBatchBtn");
    var closeNewBatchModal = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the New Batch modal 
    newBatchBtn.onclick = function() {
        newBatchModal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the New Batch modal
    closeNewBatchModal.onclick = function() {
        newBatchModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the New Batch modal, close it
    window.onclick = function(event) {
        if (event.target == newBatchModal) {
            newBatchModal.style.display = "none";
        }
    }

    // Get modal elements for Remove Batch
    var removeBatchModal = document.getElementById("removeBatchModal");
    var removeBatchBtn = document.getElementById("removeBatchBtn");
    var closeRemoveBatchModal = document.getElementsByClassName("close")[1];

    // When the user clicks the button, open the Remove Batch modal 
    removeBatchBtn.onclick = function() {
        removeBatchModal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the Remove Batch modal
    closeRemoveBatchModal.onclick = function() {
        removeBatchModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the Remove Batch modal, close it
    window.onclick = function(event) {
        if (event.target == removeBatchModal) {
            removeBatchModal.style.display = "none";
        }
    }
</script>


</html>
