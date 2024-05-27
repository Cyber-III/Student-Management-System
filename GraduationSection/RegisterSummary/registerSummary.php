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
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style-registerSummary.css"> <!--Relevent PHP File CSS-->
</head>
<body>
    
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">View My Details</h1>
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/win.png" class="img-fluid" alt="win image" height="300px" width="600px">
                
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <div class="Rectangle">
                <button id="print-button" type="button" class="print btn btn-primary mb-3">Print</button>
                    <table id="table" class="table">
                        <thead>
                            <tr>
                                <th colspan="2" scope="col">Graduation Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Center</th>
                                <td><span class="form-value"><?php echo isset($lec) ? $lec : ''; ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Program</th>
                                <td><span class="form-value"><?php echo isset($course) ? $course : ''; ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Batch</th>
                                <td><span class="form-value"><?php echo isset($batch_number) ? $batch_number : ''; ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Student ID</th>
                                <td><span class="form-value"><?php echo isset($username) ? $username : ''; ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Name</th>
                                <td><span><?php echo isset($student_name) ? $student_name : ''; ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Name to be appeared</th>
                                <td><span><?php echo isset($student_name) ? $student_name : ''; ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Graduation</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">Graduation Date</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">Time</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">Venue</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">Number of Passes</th>
                                <td>0</td>
                            </tr>

                        </tbody>
                        <thead>
                            <tr>
                                <th colspan="2" scope="col">Graduation Payments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Graduation Payment</th>
                                <td>Pending</td>
                            </tr>
                            <tr>
                                <th scope="row">Refundable Payment</th>
                                <td>Pending</td>
                            </tr>
                        </tbody>
                    </table>
                    <marquee><p class="information-text">Please note that session time and the seating plan will be informed one week prior the ceremony.</p></marquee>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('print-button').addEventListener('click', function () {
        const printContent = document.getElementById('table').outerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();  // Reload the page to restore the original content
    });
</script>
    
</body>
</html>