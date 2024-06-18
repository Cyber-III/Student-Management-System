<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');
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
          <option value="batch1">Batch 1</option>
          <option value="batch2">Batch 2</option>
          <option value="batch3">Batch 3</option>
          <!-- Add more options as needed -->
        </select>
        <button type="button" onclick="removeBatchField(this)">Remove</button>
      `;
      batchContainer.appendChild(newBatchField);

      // Change button text to "Add Another Batch" after the first batch is added
      const addBatchButton = document.getElementById('addBatchButton');
      addBatchButton.textContent = 'Add Another Batch';
    }

    function removeBatchField(button) {
      const batchContainer = document.getElementById('batchContainer');
      batchContainer.removeChild(button.parentNode);
    }
  </script>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="form-content">
            <h1>Add Notice</h1>
            <form method="POST" action="add_notice_handler.php">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
                
                <label for="added_date">Added Date:</label>
                <input type="date" id="added_date" name="added_date" required>
                
                <label for="view_link">Link:</label>
                <input type="url" id="view_link" name="view_link" required>

                 <p>For Share this notice Add Batches you want !</p>
                <label for="batch">Batch:</label>
                <div id="batchContainer">
                    <div class="batchField">
                        <select name="batch[]" required>
                            <option value="batch1">Batch 1</option>
                            <option value="batch2">Batch 2</option>
                            <option value="batch3">Batch 3</option>
                            <!-- Add more options as needed -->
                        </select>
                        <button type="button" onclick="removeBatchField(this)">Remove</button>
                    </div>
                </div>
                <button type="button" id="addBatchButton" onclick="addBatchField()">Add Batch</button>
                
                <input type="submit" value="Add Notice">
            </form>
        </div>

        <div class="form-content">
            <h1>Delete Notice</h1>

            <form method="POST" action="delete_notice_handler.php">
                <label for="delete_id">ID:</label>
                <input type="text" id="delete_id" name="delete_id" required>
                <p>Delete Added Notice Enter Notice ID Here</p>

                <input type="submit" value="Delete Notice">
            </form>
        </div>
    </div>
</div>
</body>
</html>
