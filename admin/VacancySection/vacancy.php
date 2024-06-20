
<?php

session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal Dashboard</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-vacancy.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Job Portal Dashboard</h1>
        <div class="card-container">
            <div class="card">
                <h2>Add Vacancies</h2>
                <p>Create and manage job vacancies in to the student portal.</p>
                <a href="vacancytbl.php" class="btn">Go to Add Vacancies</a>
            </div>
            <div class="card">
                <h2>Show All Applicants</h2>
                <p>View all applicants for the job vacancies from the student portal.</p>
                <a href="show_applicants.php" class="btn">Go to Show All Applicants</a>
            </div>
        </div>
    </div>
</body>
</html>
