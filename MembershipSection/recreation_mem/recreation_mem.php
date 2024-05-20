<?php
// Start the session
session_start();

// Check if success parameter is set
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    // Display success message
    echo '<!-- Bootstrap alert message -->
<div class="position-fixed top-50 start-50 translate-middle alert-container">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Your request was successful. After admin review, you will receive a notification on this page.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>';
}


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
    <link rel="stylesheet" href="style-recreation_mem.css"> <!--Relevent PHP File CSS-->


</head>
<body>
    
<section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">Recreation Membership</h1>
                <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/recreation.png" class="img-fluid" alt="recreation" height="300px" width="500px">
                </div>
                <div class="form col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form action="checkout.php" method="post">
            <div class="form-outline mb-4">
                <label class="form-label" for="username"><i class="bi bi-person-circle"></i> Username</label>
                <input type="text" id="username" class="form-control form-control-lg py-3" name="username" autocomplete="off" placeholder="Enter your username" style="border-radius:25px;" required>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="student_name"><i class="bi bi-person-vcard-fill"></i> Student Name</label>
                <input type="text" id="student_name" class="form-control form-control-lg py-3" name="student_name" autocomplete="off" placeholder="Enter your name" style="border-radius:25px;" required>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="email"><i class="bi bi-envelope-fill"></i> Email</label>
                <input type="email" id="email" class="form-control form-control-lg py-3" name="email" autocomplete="off" placeholder="Enter your email" style="border-radius:25px;" required>
            </div>
            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                <input type="submit" value="Request Membership" name="request_rec" class="btn btn-warning btn-lg text-light my-2 py-3" style="width:100%; border-radius: 30px; font-weight:600; background-color: #3333ff; border-color: #3333ff; color: #FFFFFF;">
            </div>
        </form>
                </div>
            </div>
        
        </div>
    </section>
    
    
</body>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Popper.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha384-ogU4CdKg8vokppVdA5P+hCpXE3PpBhVRrK0c6ZYfw10CK4ZJktlPwwSsAmzyg54o" crossorigin="anonymous"></script>

<!-- Bootstrap JavaScript library -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-kq1PyyoRd6+jvLmu+J1o7wrVFa65lub80OmNkx631OfshC+EAk5sRSpG2hbPUmrg" crossorigin="anonymous"></script>

<!-- Script to dismiss Bootstrap alert message -->
<script>
    $(document).ready(function() {
        // Function to dismiss the alert message after a certain duration
        setTimeout(function() {
            $(".alert-container").fadeOut('slow'); // Adjust animation as per your preference
        }, 20000); // Adjust the duration as per your preference

        // Close alert message when the close button is clicked
        $(".btn-close").click(function() {
            $(".alert-container").fadeOut('slow'); // Adjust animation as per your preference
        });
    });
</script>


</html>