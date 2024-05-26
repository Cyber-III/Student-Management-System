<?php
session_start();

include_once('../connection.php');

// Loading the template.php
include_once('../assests/content/static/template.php');


$username = $_SESSION['username']; // Get the username from the session

// Fetch the user's batch number based on the username
$sql = "SELECT course, batch_number FROM login_tbl WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        $course = $user['course'];
        $batch_number = $user['batch_number'];

        // Fetch the schedule details based on the course and batch number
        $sql = "SELECT * FROM class_schedule WHERE course = ? AND batch = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $course, $batch_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $schedules = $result->fetch_all(MYSQLI_ASSOC); // Fetch all records
            $stmt->close();
        } else {
            die("Error in SQL query: " . $conn->error);
        }
    } else {
        die("User not found.");
    }
} else {
    die("Error in SQL query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Calendar with Google Drive Link</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
    
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6 sche-container">
                <h1 class="topic ml-10">Class Schedule</h1>
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
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <div class="border-rectangle">
                    <?php if ($schedules): ?>
                        <?php foreach ($schedules as $schedule): ?>
                            <div class="schedule-item">
                                <p><span class="schedule-title">Module: </span> <?= htmlspecialchars($schedule['module']) ?></p>
                                <p><span class="schedule-title">Lecturer: </span> <?= htmlspecialchars($schedule['lecturer']) ?></p>
                                <p><span class="schedule-title">Date: </span> <?= htmlspecialchars($schedule['date']) ?></p>
                                <p><span class="schedule-title">Time: </span> <?= htmlspecialchars($schedule['time']) ?></p>
                                <p><span class="schedule-title">Hall: </span> <?= htmlspecialchars($schedule['hall']) ?></p>
                                <p class="notes">
                                    <span class="schedule-title">Notes: </span>
                                    <span class="schedule-data"><?= htmlspecialchars($schedule['notes']) ?></span>
                                </p>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No schedule found for batch <?= htmlspecialchars($batch_number) ?>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
