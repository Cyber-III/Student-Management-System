<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-registerGrad.css"> <!--Relevent PHP File CSS-->
</head>
<body>
    
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">Graduation Registration</h1>
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/g2.png" class="img-fluid" alt="Graduation image" height="300px" width="600px">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <div class="Rectangle">
                    <div class="Register-Details">
                        <div class="detail"><span>Student ID:</span> <p><span><?php echo isset($username) ? $username : ''; ?></span></p></div>
                        <div class="detail"><span>Name:</span> <p><span><?php echo isset($student_name) ? $student_name : ''; ?></span></p></div>
                        <div class="detail"><span>Student Status:</span> <p class="status">Active</p></div>
                        <div class="detail"><span>Course Completion:</span> <p class="Completion">Not Complete</p></div>
                        <div class="detail"><span>Graduation Eligibility:</span> <p class="Eligibility">Not Eligible</p></div>
                        <div class="detail"><span>Payments:</span> <p class="Payments">Outstanding</p></div>
                        <div class="detail"><span>Penalty:</span> <p class="Penalty">No Penalties</p></div>
                        <div class="detail"><span>Return of Library Books:</span> <p class="Return">Returned</p></div>

                        <p class="update">NEW GRADUATION SCHEDULE STILL NOT UPDATED</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    
    
</body>
</html>

