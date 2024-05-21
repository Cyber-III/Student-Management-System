<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Calendar</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="calendar">
        <div class="month">
            <h1 id="monthYear"></h1>
        </div>
        <div class="days-of-week">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>
        <div class="dates" id="dates"></div>
    </div>
    
    <script src="script.js"></script>    
</body>
</html>
