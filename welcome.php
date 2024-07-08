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

// Fetch the latest notices from the database for the bottom bar section
$sql_bottom_bar = "SELECT subject, added_date FROM notice ORDER BY added_date DESC LIMIT 5";
$result_bottom_bar = $conn->query($sql_bottom_bar);

// Create an array to hold the notices for the bottom bar
$notices_bottom_bar = [];

if ($result_bottom_bar->num_rows > 0) {
    // Fetch the data
    while ($row = $result_bottom_bar->fetch_assoc()) {
        $notices_bottom_bar[] = $row;
    }
}

// Initialize variables for user info and notices
$notices_notice_board = [];

// Check if the user is logged in and retrieve username
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch course and batch number for the logged-in user
    $sql_user_info = "SELECT course, batch_number FROM login_tbl WHERE username = ?";
    $stmt_user_info = $conn->prepare($sql_user_info);
    if ($stmt_user_info) {
        $stmt_user_info->bind_param('s', $username);
        $stmt_user_info->execute();
        $result_user_info = $stmt_user_info->get_result();
        $user = $result_user_info->fetch_assoc();
        $stmt_user_info->close();

        if ($user) {
            $batch_number = $user['batch_number'];

            // Store batch number in session (optional)
            $_SESSION['batch_number'] = $batch_number;

            // Fetch notices for the user's batch number
            $sql_notices = "SELECT subject, added_date FROM `batch-notice` WHERE batch_number = ? ORDER BY added_date DESC LIMIT 5";
            $stmt_notices = $conn->prepare($sql_notices);
            if ($stmt_notices) {
                $stmt_notices->bind_param('s', $batch_number);
                $stmt_notices->execute();
                $result_notices = $stmt_notices->get_result();
                while ($row = $result_notices->fetch_assoc()) {
                    $notices_notice_board[] = $row;
                }
                $stmt_notices->close();
            } else {
                die("Error in SQL query: " . $conn->error);
            }
        } else {
            die("User not found.");
        }
    } else {
        die("Error in SQL query: " . $conn->error);
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Welcome Page</title>

    <!-- Upper Icon -->
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
                <?php if (!empty($notices_bottom_bar)): ?>
                    <?php foreach ($notices_bottom_bar as $index => $notice): ?>
                        <?php echo htmlspecialchars($notice['subject']); ?>
                        <?php if ($index < count($notices_bottom_bar) - 1): ?>
                            <?php echo " | "; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                
                    <!-- No notices available. &nbsp;&nbsp;&nbsp;<span style="color: red;">Please Check the Notice Board</span> -->
                <?php endif; ?>
            </marquee>
        </p>
    </div>
</div>

<!-- Add a floating button to the top-right corner -->
<button class="notice-btn" id="notice-btn"><i class='bx bxs-bell-ring'></i></button>

<!-- Notice Board Section -->
<div class="notice-board" id="notice-board">
    <h2>Notice Board</h2>
    <?php if (!empty($notices_notice_board)): ?>
        <?php foreach ($notices_notice_board as $notice): ?>
            <?php
                // Check if the notice was added in the last 24 hours
                $addedTime = strtotime($notice['added_date']);
                $currentTime = time();
                $twentyFourHoursAgo = $currentTime - (24 * 60 * 60);
                $isRecent = $addedTime >= $twentyFourHoursAgo;

                // Format the date
                $formattedDate = date("d M Y", $addedTime);
            ?>
            <div class="notice <?php echo $isRecent ? 'recent' : ''; ?>">
                <p><?php echo htmlspecialchars($notice['subject']); ?></p>
                <small><?php echo $formattedDate; ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notices available for your batch.</p>
    <?php endif; ?>
</div>

<script>
    const noticeBtn = document.getElementById('notice-btn');
    const noticeBoard = document.getElementById('notice-board');

    noticeBtn.addEventListener('click', () => {
        noticeBoard.classList.toggle('show');
    });
</script>
</body>
</html>
