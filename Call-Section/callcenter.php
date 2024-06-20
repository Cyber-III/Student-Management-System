<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch the latest details from the database
$sql = "SELECT * FROM call_center_details ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $tel = $row['tel'];
    $email = $row['email'];
    $address = $row['address'];
} else {
    $tel = "Not available";
    $email = "Not available";
    $address = "Not available";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-callcenter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Additional styles specific to this page can be added here */
    </style>
</head>
<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="pics/call.png" class="img-fluid" alt="call">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <div class="contact-details mt-4">
                        <p class="topic">Contact Details</p>
                        <hr>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="tel"><i class="bi bi-phone-fill"></i> Tel:</label>
                            <p><a href="tel:<?php echo htmlspecialchars($tel); ?>"><?php echo htmlspecialchars($tel); ?></a></p>
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="email"><i class="bi bi-envelope-fill"></i> Email:</label>
                            <p><a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a></p>
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="address"><i class="bi bi-envelope-fill"></i> Address:</label>
                            <p><?php echo htmlspecialchars($address); ?></p>
                        </div>
                    </div>
                    <!-- Social Icons -->
                    <div class="social-icons-container">
                        <a href="https://www.instagram.com/icbtsrilanka/" target="_blank"> <img src="icons/instagram.png" alt="" height="32px" width="32px"> </a>
                        <a href="https://www.facebook.com/ICBTsrilanka/" target="_blank"> <img src="icons/facebook.png" alt="" height="32px" width="32px"> </a>
                        <a href="https://www.youtube.com/channel/UC3bDumjXNsq2ixYkUT7wT_g" target="_blank"> <img src="icons/youtube.png" alt="" height="32px" width="32px"> </a>
                        <a href="https://www.linkedin.com/school/icbtcampus/" target="_blank"> <img src="icons/linkedin.png" alt="" height="32px" width="32px"> </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
