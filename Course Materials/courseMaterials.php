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
    <title>Course Materials</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="courseMateriel-style.css">
</head>
<body>
    <div class="container">
        <a href="Course Materials\Images\no data.jpg">
            <img src="../../../Course Materials/Images/no data.jpg" alt="No Records Found" class="image">
        </a>
        <br>
        <p>No Records Found.</p>
    </div>
</body>
</html>
