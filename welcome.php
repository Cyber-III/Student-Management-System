<?php
// Start the session
session_start();

// Include the database connection
include_once('connection.php');

// Loading the HTML template
require './assests/content/static/template.php';

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the latest notices from the database
$sql = "SELECT subject, added_date FROM notice ORDER BY added_date DESC LIMIT 5";
$result = $conn->query($sql);

// Create an array to hold the notices
$notices = [];

if ($result->num_rows > 0) {
    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-welcome.css">
    <title>Welcome Page</title>

    <!--Upper Icon-->
    <link rel="icon" type="image/png" sizes="32x32" href="pics/favicon-32x32.png">
    
</head>
<body>
    
<!-- Main pic of welcome page -->
<div class="main-pic">
    <img class="logo" src="./pics/L3.png" alt="Logo">
</div>

<!-- Bottom Bar Text -->
<div class="bottom-bar">
    <div class="text">
        <p>
            <marquee behavior="scroll" direction="left">
                <?php if (!empty($notices)): ?>
                    <?php foreach ($notices as $index => $notice): ?>
                        <?php echo htmlspecialchars($notice['subject']); ?>
                        <?php if ($index < count($notices) - 1): ?>
                            <?php echo " | "; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php echo " | "; ?> &nbsp;<span style="color: yellow; font-weight:500;">Please Check the Notice Board</span>
                <?php else: ?>
                    No notices available. &nbsp;&nbsp;&nbsp;<span style="color: red;">Please Check the Notice Board</span>
                <?php endif; ?>
            </marquee>
        </p>
    </div>
</div>

<!-- Notice Board Section -->
<div class="notice-board">
    <h2>Notice Board</h2>
    <?php if (!empty($notices)): ?>
        <?php foreach ($notices as $notice): ?>
            <?php
                // Check if the notice was added in the last 24 hours
                $isRecent = (time() - strtotime($notice['added_date'])) <= 86400;
            ?>
            <div class="notice <?php echo $isRecent ? 'recent' : ''; ?>">
                <p><?php echo htmlspecialchars($notice['subject']); ?></p>
                <small><?php echo date("d M Y", strtotime($notice['added_date'])); ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notices available.</p>
    <?php endif; ?>
</div>

</body>
</html>