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
    
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6 fixed-image-container text-center">
                <h1 class="graduationSch">Graduation Schedule 2024</h1>
                <img src="pics/g1.png" class="img-fluid fixed-image" alt="Graduation Image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <div class="border-rectangle">
                    <div class="schedule-item">
                        <p><span class="schedule-title">Graduation Name:</span> ABC Campus Graduation</p>
                        <p><span class="schedule-title">Date:</span> 2024-02-23</p>
                        <p><span class="schedule-title">Time:</span> 11:00 AM</p>
                        <p><span class="schedule-title">Location:</span> BMICH</p>
                        <p><span class="schedule-title">Program:</span> HND Graduation Ceremony</p>
                    </div>
                    <div class="schedule-item">
                        <p><span class="schedule-title">Graduation Name:</span> ABC Campus Graduation</p>
                        <p><span class="schedule-title">Date:</span> 2024-01-23</p>
                        <p><span class="schedule-title">Time:</span> 10:00 AM</p>
                        <p><span class="schedule-title">Location:</span> BMICH</p>
                        <p><span class="schedule-title">Program:</span> Undergraduate Ceremony</p>
                    </div>
                    <div class="schedule-item">
                        <p><span class="schedule-title">Graduation Name:</span> DEF Campus Graduation</p>
                        <p><span class="schedule-title">Date:</span> 2024-02-15</p>
                        <p><span class="schedule-title">Time:</span> 02:00 PM</p>
                        <p><span class="schedule-title">Location:</span> BMICH</p>
                        <p><span class="schedule-title">Program:</span> Postgraduate Ceremony</p>
                    </div>
                    <div class="schedule-item">
                        <p><span class="schedule-title">Graduation Name:</span> GHI Campus Graduation</p>
                        <p><span class="schedule-title">Date:</span> 2024-03-10</p>
                        <p><span class="schedule-title">Time:</span> 11:00 AM</p>
                        <p><span class="schedule-title">Location:</span> BMICH</p>
                        <p><span class="schedule-title">Program:</span> Doctoral Ceremony</p>
                    </div>
                    <!-- Add more schedule items as needed -->
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

    
    
</body>
</html>