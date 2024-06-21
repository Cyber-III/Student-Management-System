<?php
// Start the session
session_start();

// Include the database connection
include_once('connection.php');

// Loading the HTML template
require './assests/content/static/template.php';

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="style-welcome.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pics/favicon-32x32.png">

</head>
<body>
    
<!-- Main pic of welcome page -->
<div class="main-pic">
    <img class="logo" src="./pics/L3.png" alt="Logo">
</div>

</body>
</html>
