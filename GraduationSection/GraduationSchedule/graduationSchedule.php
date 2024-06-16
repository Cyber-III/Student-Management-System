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
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-graduationSchedule.css"> <!--Relevent PHP File CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
    
<section>
    <div class="container2">
            <div class="sche-container">
                <h1 class="topic">Graduation Schedule 2024</h1>
                <img src="pics/g1.png" class="img" alt="Graduation Image">
            </div>
            <div class="side_container">
                <div class="border-rectangle">
                    <!-- each schedule-card will displayed like below -->
                    <div class="schedule-card">
                        <div class="card-header">
                                    <h5>ABC Campus Graduation</h5>
                        </div>
                        <div class="card-body">
                                    <p><span class="schedule-title">Date: </span> 2024-01-23</p>
                                    <p><span class="schedule-title">Time: </span> 10:00 AM</p>
                                    <p><span class="schedule-title">Location: </span> BMICH</p>
                                    <p><span class="schedule-title">Program: </span> Undergraduate Ceremony</p>
                        </div>
                    </div>
                    <div class="schedule-card">
                        <div class="card-header">
                                    <h5>ABC Campus Graduation</h5>
                        </div>
                        <div class="card-body">
                                    <p><span class="schedule-title">Date: </span> 2024-01-23</p>
                                    <p><span class="schedule-title">Time: </span> 10:00 AM</p>
                                    <p><span class="schedule-title">Location: </span> BMICH</p>
                                    <p><span class="schedule-title">Program: </span> Undergraduate Ceremony</p>
                        </div>
                    </div>
                    <div class="schedule-card">
                        <div class="card-header">
                                    <h5>ABC Campus Graduation</h5>
                        </div>
                        <div class="card-body">
                                    <p><span class="schedule-title">Date: </span> 2024-01-23</p>
                                    <p><span class="schedule-title">Time: </span> 10:00 AM</p>
                                    <p><span class="schedule-title">Location: </span> BMICH</p>
                                    <p><span class="schedule-title">Program: </span> Undergraduate Ceremony</p>
                        </div>
                    </div>
                    <div class="schedule-card">
                        <div class="card-header">
                                    <h5>ABC Campus Graduation</h5>
                        </div>
                        <div class="card-body">
                                    <p><span class="schedule-title">Date: </span> 2024-01-23</p>
                                    <p><span class="schedule-title">Time: </span> 10:00 AM</p>
                                    <p><span class="schedule-title">Location: </span> BMICH</p>
                                    <p><span class="schedule-title">Program: </span> Undergraduate Ceremony</p>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            
        
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

    
    
</body>
</html>