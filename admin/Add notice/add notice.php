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
                
                <label for="view_link"> Link:</label>
                <input type="url" id="view_link" name="view_link" required>
                
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
