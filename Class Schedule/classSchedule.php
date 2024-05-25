<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');


// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch the latest uploaded link
$latestLinkQuery = "SELECT link FROM drive_links ORDER BY uploaded_at DESC LIMIT 1";
$latestLinkResult = mysqli_query($conn, $latestLinkQuery);
$latestLink = mysqli_fetch_assoc($latestLinkResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Calendar with Google Drive Link</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="calendar">
            <div class="month">
                <h1 id="monthYear"></h1>
            </div>
            <div class="days-of-week">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <div class="dates" id="dates"></div>
        </div>

        <!-- Display uploaded link -->
        <div class="link-section">
            <div class="border-rectangle">
                <div class="uploaded-schedule">
                    <div class="link-display">
                        <?php
                            if ($latestLink) {
                                echo '<p>Weekly Schedule:</p>';
                                echo '<button class="download-btn"><a href="' . $latestLink['link'] . '" target="_blank" download>Download Link</a></button>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>    
</body>
</html>


