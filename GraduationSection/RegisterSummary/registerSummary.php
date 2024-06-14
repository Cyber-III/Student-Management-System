<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch data from login_tbl and graduation_summary tables
$query = "SELECT l.student_name, l.course, l.batch_number, l.lec, 
                 g.graduation, g.graduation_date, g.time, g.venue, 
                 g.number_of_passes, g.graduation_payment, g.refundable_payment
          FROM login_tbl l
          LEFT JOIN graduation_summary g ON l.username = g.username
          WHERE l.username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style-template.css"> <!-- Template File CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style-registerSummary.css"> <!-- Relevant PHP File CSS -->
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
                                <td><span class="form-value"><?php echo htmlspecialchars($userData['lec']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Program</th>
                                <td><span class="form-value"><?php echo htmlspecialchars($userData['course']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Batch</th>
                                <td><span class="form-value"><?php echo htmlspecialchars($userData['batch_number']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Student ID</th>
                                <td><span class="form-value"><?php echo htmlspecialchars($username); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Name</th>
                                <td><span><?php echo htmlspecialchars($userData['student_name']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Name to be appeared</th>
                                <td><span><?php echo htmlspecialchars($userData['student_name']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Graduation</th>
                                <td><span><?php echo htmlspecialchars($userData['graduation']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Graduation Date</th>
                                <td><span><?php echo htmlspecialchars($userData['graduation_date']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Time</th>
                                <td><span><?php echo htmlspecialchars($userData['time']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Venue</th>
                                <td><span><?php echo htmlspecialchars($userData['venue']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Number of Passes</th>
                                <td><span><?php echo htmlspecialchars($userData['number_of_passes']); ?></span></td>
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
                                <td><span><?php echo htmlspecialchars($userData['graduation_payment']); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Refundable Payment</th>
                                <td><span><?php echo htmlspecialchars($userData['refundable_payment']); ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <marquee><p class="information-text">Please note that session time and the seating plan will be informed one week prior to the ceremony.</p></marquee>
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
