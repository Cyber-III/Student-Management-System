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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-evaluation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Document</title>
    <style>

    </style>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/feedback.png" class="img-fluid custom-img" alt="feedback">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form action="https://api.web3forms.com/submit" method="POST">
                    <!-- Web3Forms Access Key -->
                    <input type="hidden" name="access_key" value="48b2fe23-344b-49c4-8644-ac156c0afb14">
                    
                    <div class="form-outline mb-4">
                        <label for="sendMessageTo" class="form-label">Send Message To</label>
                        <select class="form-select" id="sendMessageTo" name="send_message_to">
                            <option selected>Select lecturer</option>
                            <option value="Miss. Thilini">Miss. Thilini</option>
                            <option value="Mr. Akila Abeyesekara">Mr. Akila Abeyesekara</option>
                            <option value="Mr. Roshan Perera">Mr. Roshan Perera</option>
                            <option value="Mr. Amila Liyanage">Mr. Amila Liyanage</option>
                            <option value="Mr. Kasuni Wijesekara">Mr. Kasuni Wijesekara</option>
                        </select>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="Evaluation" class="form-label">Evaluation</label>
                        <textarea class="form-control" id="Evaluation" name="Evaluation" rows="6" placeholder="Enter your evaluation" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

<script>


    document.getElementById('cancelButton').addEventListener('click', function() {
        document.querySelector('form').reset(); // Reset the form
    });
</script>



</body>
</html>
