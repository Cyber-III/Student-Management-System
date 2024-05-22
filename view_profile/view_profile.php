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
    <div class="side-container">
        <div class="profilepic">
            <a href="..\view_profile\Images\graduate.png">
                <img src="..\view_profile\Images\graduate.png" alt="User Profile Icon">
            </a>
        </div>
        <div class="uni-pic-container">
            <div class = "box">
            <img src="..\view_profile\Images\uni-1.png" alt="uni-1-pic">
            </div>
            <div class = "box">
            <img src="..\view_profile\Images\uni-2.png" alt="uni-1-pic">
            </div> 
        </div>
    </div>
    

    <div class="profileTop">

        <b><h1 class="topname"> <span><?php echo isset($student_name) ? $student_name : ''; ?></span></h1></b>
        <h6><?php echo isset($course) ? $course : ''; ?></h6>
        <br>
            
            <div class="p_data">
                <div class = "personal_details">
                    <h2> Personal Details</h2>
                    <hr>
                    <br>
                    <div class = "form-row">
                        <span class="form-label">Student ID:</span>
                        <span class="form-value"><?php echo isset($username) ? $username : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">Batch No:</span>
                        <span class="form-value"><?php echo isset($batch_number) ? $batch_number : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">Gender:</span>
                        <span class="form-value"><?php echo isset($gender) ? $gender : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">Date Of Birth:</span>
                        <span class="form-value"><?php echo isset($dob) ? $dob : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label"> National Identification No:</span>
                        <span class="form-value"><?php echo isset($nic) ? $nic : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label"> E-mail:</span>
                        <span class="form-value"><?php echo isset($email) ? $email : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label"> Contact info:</span>
                        <span class="form-value"><?php echo isset($contact) ? $contact : ''; ?></span>
                    </div>
                </div>
               

            </div>
            <br><br>
            <div class="University_data">
                <div class = "uni_details">
                    <h2>University Details</h2>
                    <hr>
                    <br>
                    <div class = "form-row">
                        <span class="form-label">Awarding University:</span>
                        <span class="form-value"><?php echo isset($awarding_uni) ? $awarding_uni : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">University Number:</span>
                        <span class="form-value"><?php echo isset($uni_number) ? $uni_number : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">Local Education Center:</span>
                        <span class="form-value"><?php echo isset($lec) ? $lec : ''; ?></span>
                    </div>

                </div>
                
            </div>
    </div>
</div>

    
</body>
</html>