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
    <link rel="stylesheet" href="style-message.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Document</title>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/messages.png" class="img-fluid" alt="Message">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form action="https://api.web3forms.com/submit" method="POST">
                    <!-- Web3Forms Access Key -->
                    <input type="hidden" name="access_key" value="48b2fe23-344b-49c4-8644-ac156c0afb14">

                    <!-- Subject Field for Email -->
                    <input type="hidden" name="email_subject" id="email_subject">
                    
                    <!-- Form Fields -->
                    <div class="form-outline mb-4">
                        <label for="messageType" class="form-label">Message Type</label>
                        <select class="form-select" id="messageType" name="message_type">
                            <option selected>Choose...</option>
                            <option value="Inquiry">Inquiry</option>
                            <option value="Request">Request</option>
                            <option value="Complaint">Complaint</option>
                        </select>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="sendMessageTo" class="form-label">Send Message To</label>
                        <select class="form-select" id="sendMessageTo" name="send_message_to">
                            <option selected>Select User</option>
                            <option value="All Users">All Users</option>
                            <option value="Admin_qa Admin">Admin_qa Admin</option>
                            <option value="Akila Abeyesekara">Akila Abeyesekara</option>
                            <option value="Roshan Perera">Roshan Perera</option>
                            <option value="Amila Liyanage">Amila Liyanage</option>
                            <option value="Kasuni Wijesekara">Kasuni Wijesekara</option>
                        </select>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" required>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="6" placeholder="Enter your message" required></textarea>
                    </div>
                    
                    <button type="button" class="btn btn-secondary me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        // Set the value of the email_subject hidden field to the value of the subject input field
        var subject = document.getElementById('subject').value;
        document.getElementById('email_subject').value = subject;
    });
</script>



</body>
</html>
