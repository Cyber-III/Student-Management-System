

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-welcome.css">
    <title>Document</title>
</head>
<body>
    
    <!--Main pic of welcome page-->
    <div class="main-pic">
       <img class="logo" src="./pics/L3.png">
    </div>

    <!--Bottom Bar Text-->
    <div class="Bottom-bar">
    <div class="text">
       <p><marquee behavior="" direction="left"> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eveniet nostrum sunt quidem error, ducimus dolor natus sequi iste velit voluptatem minima nisi laudantium, nulla nobis modi dicta deserunt? Similique, delectus!</marquee> </p>
    </div>
    </div>
    

</body>
</html>

<?php
// Start the session
session_start();

// Include the database connection
include_once('connection.php');

// Loading the HTML template
require './assests/content/static/template.php';


?>
