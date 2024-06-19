<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Function to sanitize input
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Fetch awarding batch numbers
$com_notice = [];
$result2 = mysqli_query($conn, "SELECT * batch_no FROM notice");



// Fetch awarding batch numbers
$batch_numbers = [];
$result2 = mysqli_query($conn, "SELECT DISTINCT batch_no FROM batches");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['batch_no'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['common_notice_form'])) {
        // Handle common notice form submission
        $subject = sanitize_input($conn, $_POST['subject']);
        $added_date = sanitize_input($conn, $_POST['added_date']);
        $view_link = sanitize_input($conn, $_POST['view_link']);
        
        $query = "INSERT INTO notice (subject, added_date, view_link) VALUES ('$subject', '$added_date', '$view_link')";
        
        if (mysqli_query($conn, $query)) {
            echo "Common notice added successfully.";
        } else {
            if (mysqli_errno($conn) == 1062) {
                echo "You have already inserted this notice.";
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['batch_notice_form'])) {
        // Handle batch notice form submission
        $subject = sanitize_input($conn, $_POST['subject']);
        $added_date = sanitize_input($conn, $_POST['added_date']);
        $view_link = sanitize_input($conn, $_POST['view_link']);
        $batches = $_POST['batch'];
        
        foreach ($batches as $batch) {
            $batch = sanitize_input($conn, $batch);
            $query = "INSERT INTO `batch-notice` (batch_number, subject, added_date, view_link) VALUES ('$batch', '$subject', '$added_date', '$view_link')";
            
            if (mysqli_query($conn, $query)) {
                echo "Batch notice added successfully.";
            } else {
                if (mysqli_errno($conn) == 1062) {
                    echo "You have already inserted this notice for batch $batch.";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Delete Notice</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="add notice.css">
    <script>
        function addBatchField() {
            const batchContainer = document.getElementById('batchContainer');
            const newBatchField = document.createElement('div');
            newBatchField.className = 'batchField';
            newBatchField.innerHTML = `
                <select name="batch[]" required>
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" onclick="removeBatchField(this)" class="delete-link">Remove</button>
            `;
            batchContainer.appendChild(newBatchField);

            // Change button text to "Add Another Batch" after the first batch is added
            const addBatchButton = document.getElementById('addBatchButton');
            addBatchButton.textContent = 'Add Batch';
        }

        function removeBatchField(button) {
            const batchContainer = document.getElementById('batchContainer');
            batchContainer.removeChild(button.parentNode);
        }
    </script>
</head>
<body>
<div class="container">
    <div class="form-container-1">
        <button type="button" class="view-link" onclick="openBatchNotice()">Manage</button>

        <div class="form-content">
            <h1 style="bold">Batch Notice</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="batch_notice_form">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
                
                <label for="added_date">Added Date:</label>
                <input type="date" id="added_date" name="added_date" required>
                
                <label for="view_link">Link:</label>
                <input type="text" id="view_link" name="view_link" >

                <p>Select Batches:</p>
                <div id="batchContainer">
                    <div class="batchField">
                      <select name="batch[]" required>
                          <option value="">Select Batch Number</option>
                          <?php foreach ($batch_numbers as $batch_number): ?>
                              <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                          <?php endforeach; ?>
                      </select>
                      <input type="hidden" onclick="removeBatchField(this)" class="delete-link"></input>
                    </div>
                </div>
                <button type="button" id="addBatchButton" onclick="addBatchField()" class="view-link">Add Batch</button>

                <input type="submit" value="Add Notice" class="view-link">
            </form>
        </div>


        
    </div>

    <div class="form-container-2">
        <div class="form-content">
        <button type="button" class="view-link" onclick="openNotice()">Manage</button>

            <h1>Common Notice</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="common_notice_form">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
                
                <label for="added_date">Added Date:</label>
                <input type="date" id="added_date" name="added_date" required>
                
                <label for="view_link">Link:</label>
                <input type="text" id="view_link" name="view_link" >

                <input type="submit" value="Add Notice" class="view-link">
            </form>
        </div>
    </div>
</div>
</body>

    <script>
        function openBatchNotice() {
            // Redirect to the batch-notice.php file
            window.location.href = 'batch-notice.php';
        }

        function addBatchField() {
            // Function to dynamically add batch fields
            var batchContainer = document.getElementById('batchContainer');
            var newField = document.createElement('div');
            newField.className = 'batchField';
            newField.innerHTML = `
                <select name="batch[]" required>
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" onclick="removeBatchField(this)" class="delete-link">
            `;
            batchContainer.appendChild(newField);
        }

        function removeBatchField(element) {
            // Function to remove batch fields
            var batchContainer = document.getElementById('batchContainer');
            batchContainer.removeChild(element.parentNode);
        }
    </script>

    <script>
        function openNotice() {
            // Redirect to the batch-notice.php file
            window.location.href = 'notice.php';
        }

        function addBatchField() {
            // Function to dynamically add batch fields
            var batchContainer = document.getElementById('batchContainer');
            var newField = document.createElement('div');
            newField.className = 'batchField';
            newField.innerHTML = `
                <select name="batch[]" required>
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" onclick="removeBatchField(this)" class="delete-link">
            `;
            batchContainer.appendChild(newField);
        }

        function removeBatchField(element) {
            // Function to remove batch fields
            var batchContainer = document.getElementById('batchContainer');
            batchContainer.removeChild(element.parentNode);
        }
    </script>
</html>
