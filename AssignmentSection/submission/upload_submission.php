<?php
session_start();
include_once('../../connection.php');
include_once('../../assests/content/static/template.php');

$username = $_SESSION['username'];

// Fetch user course and batch number from login_tbl
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

        // Store batch number in session
        $_SESSION['batch_number'] = $batch_number;

        // Fetch assignment schedules for the user's course and batch number
        $sql = "SELECT * FROM assignment_schedule WHERE course = ? AND batch_number = ?";
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

$message = isset($_GET['message']) ? $_GET['message'] : '';
$file_path = isset($_GET['file_path']) ? $_GET['file_path'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-submission.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body class="one">
<div class="container">
    <marquee><p class="text">*Please note that when renaming your document, use your username (CL number) and assignment name. Ex: 'CL_HDCSE_CMU_XXX_XX_Networking_Practicle.pdf'</p></marquee>

    <h1 class="topic">Assignment Submission</h1>

    <?php if ($message == 'submitted'): ?>
        <div class="alert alert-success">Your document was submitted successfully.</div>
    <?php elseif ($message == 'updated'): ?>
        <div class="alert alert-success">Your document was updated successfully.</div>
    <?php elseif ($message == 'viewed'): ?>
        <div class="alert alert-info">You can view your submitted document <a href="<?= htmlspecialchars($file_path) ?>" target="_blank">here</a>.</div>
    <?php elseif ($message == 'empsub'): ?>
        <div class="alert alert-danger">Please select a file before pressing the submit button.</div>
    <?php elseif ($message == 'nosub'): ?>
        <div class="alert alert-danger">No submission found yet for the specified assignment.</div>
    <?php endif; ?>

    <div class="border-rectangle">
        <?php if ($schedules): ?>
            <?php foreach ($schedules as $schedule): ?>
                <div class="schedule-item">
                    <p><span class="schedule-title">Assignment Name: <?= htmlspecialchars($schedule['assignment_name']) ?> </span> </p>
                    <p><span class="schedule-title">Module Name: <?= htmlspecialchars($schedule['module_name']) ?> </span> </p>
                    <p><span class="schedule-title">Module Code: <?= htmlspecialchars($schedule['module_code']) ?> </span> </p>
                    <p><span class="schedule-title">Date: <?= htmlspecialchars($schedule['date_of_issue']) ?></span> </p>
                    <?php if ($schedule['allow_submission']): ?>
                        <form action="upload.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="module_name" value="<?= htmlspecialchars($schedule['module_name']) ?>">
                            <input type="hidden" name="assignment_name" value="<?= htmlspecialchars($schedule['assignment_name']) ?>">
                            <input type="hidden" name="module_code" value="<?= htmlspecialchars($schedule['module_code']) ?>">
                            <input type="file" name="file"> <br> <br>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            <button type="submit" name="view" class="btn btn-secondary">View</button>
                        </form>
                    <?php else: ?>
                        <p>Submission is not allowed for this assignment at the moment.</p>
                    <?php endif; ?>
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
