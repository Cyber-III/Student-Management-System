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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-view_profile.css">
</head>
<body>
    <div class="container">
        <!-- //adding user profile image -->
<div class="profilepic">
    <a href="..\view_profile\Images\User Icon.png">
        <img src="..\view_profile\Images\User Icon.png" alt="User Profile Icon">
    </a>
</div>

    <div class="profileTop">

        <b><h1 class="topname"> <span><?php echo isset($student_name) ? $student_name : ''; ?></span></h1></b>
        <h6><?php echo isset($course) ? $course : ''; ?></h6>
        <br><br>
            
            <div class="p_data">
                <h2 class="personal_details">Personal Details</h2><hr>
                <h4 class="studentid">Student ID :  <span><?php echo isset($username) ? $username : ''; ?></span></h4>

                <h4 class="Batch_No"> Batch No :  <span><?php echo isset($batch_number) ? $batch_number   : ''; ?></span></h4>

                <h4 class="Gender">   Gender :  <span><?php echo isset($gender) ? $gender : ''; ?></span></h4>

                <h4 class="birth_date">Birth date :  <span><?php echo isset($dob) ? $dob : ''; ?></span></h4>

                <h4 class="NIC">       National Identification No. :  <span><?php echo isset($nic) ? $nic : ''; ?></span></h4>

                <h4 class="Email">     Email :  <span><?php echo isset($email) ? $email : ''; ?></span></h4>

                <h4 class="Contact">   Contact :  <span><?php echo isset($contact) ? $contact : ''; ?></span></h4>
                <br><br>
            </div>

            <div class="University_data">
                <h2 class="uni_details">University Details</h2><hr>
                <h4 class="uni">Awarding University :  <span><?php echo isset($awarding_uni) ? $awarding_uni : ''; ?></span></h4>
                <h4 class="uni_no">University Number :  <span><?php echo isset($uni_number) ? $uni_number   : ''; ?></span></h4>
                <h4 class="center">Local Education Center :  <span><?php echo isset($lec) ? $lec : ''; ?></span></h4>
            </div>
    </div>
    </div>

    
</body>
</html>