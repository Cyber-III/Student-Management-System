<?php
session_start();

include_once('../../connection.php');

// Loading the template.php
include_once('../../assests/content/static/template.php');


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

        // Fetch the assignment schedules based on the course and batch number
        $sql = "SELECT * FROM assignment_schedule WHERE course = ? AND batch_number = ?";
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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Submission</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-submission.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <h1 class="topic">Assignment Submission</h1>
    <div class="border-rectangle">
        <?php if ($schedules): ?>
            <?php foreach ($schedules as $schedule): ?>
                <div class="schedule-item">
                    <p><span class="schedule-title">Module Name: </span> <?= htmlspecialchars($schedule['module_name']) ?></p>
                    <p><span class="schedule-title">Module Code: </span> <?= htmlspecialchars($schedule['module_code']) ?></p>
                    <p><span class="schedule-title">Date of Issue: </span> <?= htmlspecialchars($schedule['date_of_issue']) ?></p>
                    <p><span class="schedule-title">Date of Submit: </span> <?= htmlspecialchars($schedule['date_of_submit']) ?></p>
                    <div class="mt-5">
                        <!-- Upload form -->
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                                <div class="hidden">
                                    <input type="hidden" name="module_id" value="<?= htmlspecialchars($schedule['module_id']) ?>" style="display: none;">
                                </div>
                             <!-- File input -->
                            <input type="file" name="assignment_file" required><br><br>
                            <button type="submit" class="btn btn-primary">Upload Assignment</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No assignment schedule found for batch <?= htmlspecialchars($batch_number) ?>.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
