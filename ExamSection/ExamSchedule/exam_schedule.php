<?php
session_start();

include_once('../../connection.php');

// Loading the template.php
include_once('../../assests/content/static/template.php');


$username = $_SESSION['username'];

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

        // Fetch the assignment schedules based on the course and batch number
        $sql = "SELECT * FROM exam_schedule WHERE course = ? AND batch_number = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $course, $batch_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $schedules = $result->fetch_all(MYSQLI_ASSOC);
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
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="exam_schedule-style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body class="one">
    <div class="container">
        <h1 class="topic">Exam Schedule</h1>
        <div class="border-rectangle">
            <?php if ($schedules): ?>
                <?php foreach ($schedules as $schedule): ?>
                    <div class="schedule-item">
                        <p><span class="schedule-title">Exam Name: <?= htmlspecialchars($schedule['exam_name']) ?> </span> </p>
                        <p><span class="schedule-title">Date: <?= htmlspecialchars($schedule['date']) ?> </span> </p>
                        <p><span class="schedule-title">Time: <?= htmlspecialchars($schedule['time']) ?></span> </p>
                        <p><span class="schedule-title">Location: <?= htmlspecialchars($schedule['location']) ?></span> </p>
                        <p><span class="schedule-title">Hours: <?= htmlspecialchars($schedule['hours']) ?></span> </p>
                        
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No assignment schedule found for <?= htmlspecialchars($course) ?> <?= htmlspecialchars($batch_number) ?>.</p>
            <?php endif; ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
