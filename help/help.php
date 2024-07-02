<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');


$query = "SELECT * FROM manuals ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$manual = mysqli_fetch_assoc($result);


include_once('../assests/content/static/template.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-help.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Document</title>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/Instruction manual-cuate.png" class="img-fluid" alt="Message">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <h1>User Manual</h1>
                <form action="download_manual.php" method="post">
                    <button type="submit" class="btn1">Download</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>