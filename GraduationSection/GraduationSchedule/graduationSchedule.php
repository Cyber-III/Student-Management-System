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
    <title>Graduation Schedule 2024</title>
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
                    <?php
                    // Fetch graduation schedule data from the database
                    $query = "SELECT * FROM graduation";
                    $result = mysqli_query($conn, $query);

                    // Check if there are any results
                    if(mysqli_num_rows($result) > 0) {
                        // Loop through each row and generate HTML
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="schedule-card">';
                            echo '    <div class="card-header">';
                            echo '        <h5>' . htmlspecialchars($row['name']) . '</h5>';
                            echo '    </div>';
                            echo '    <div class="card-body">';
                            echo '        <p><span class="schedule-title">Date: </span>' . htmlspecialchars($row['date']) . '</p>';
                            echo '        <p><span class="schedule-title">Time: </span>' . htmlspecialchars($row['time']) . '</p>';
                            echo '        <p><span class="schedule-title">Location: </span>' . htmlspecialchars($row['location']) . '</p>';
                            echo '        <p><span class="schedule-title">Program: </span>' . htmlspecialchars($row['course']) . '</p>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No graduation schedules found.</p>';
                    }
                    ?>
                </div>
            </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
