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
    <title>Library Membership</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-library_mem.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">Library Membership</h1>
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="pics/library.png" class="img-fluid" alt="Library image" height="300px" width="600px">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form action="request_lib.php" method="post">
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
                            <input type="submit" value="Request Membership" name="request_lib" class="btn btn-warning btn-lg text-light my-2 py-3" style="width:100%; border-radius: 30px; font-weight:600; background-color: #3333ff; border-color: #3333ff; color: #FFFFFF;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


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









</body>




</html>




<!-- <h4 class="studentid">Student ID :  <span><?php echo isset($username) ? $username : ''; ?></span></h4> -->