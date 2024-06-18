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
$result = mysqli_query($conn, "SELECT DISTINCT course FROM login_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
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
</head>

<body>

    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">The batch was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The batch was deleted successfully.</div>
    <?php endif; ?>
    <!-- <button class="batch view-link" id="newBatchBtn">New Batch +</button> -->
    <button class="batch delete-link" id="removeBatchBtn">Drop Batch -</button>

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

    <!-- The Remove Modal -->
    <div id="removeBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeRemoveBatchModal" class="close">&times;</span>
            <h2>Remove Batch</h2>
            <form action="batch_createdelete.php" method="POST">
                <div class="form-group">
                    <label for="batch_number">Batch Number:</label>
                    <select id="batch_number" name="batch_number" required>
                        <option value="">Select Batch Number</option>
                        <?php foreach ($batch_numbers as $batch_number): ?>
                            <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <br>
                <button type="submit" class="batch delete-link">Remove Batch</button>
            </form>
        </div>
    </div>

</body>

<script>
    // Get modal elements for Remove Batch
    var removeBatchModal = document.getElementById("removeBatchModal");
    var removeBatchBtn = document.getElementById("removeBatchBtn");
    var closeRemoveBatchModal = document.getElementById("closeRemoveBatchModal");

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
