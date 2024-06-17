<?php
session_start();
include_once('../connection.php');

include_once('../assests/content/static/template.php');

$username = $_SESSION['username'];

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

        $sql = "SELECT * FROM viva_schedules WHERE course = ? AND batch_number = ?";
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

// Check for messages
$message = isset($_GET['message']) ? $_GET['message'] : '';
$file_path = isset($_GET['file_path']) ? $_GET['file_path'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="exam_submission-style.css">

</head>
<body class="one">
<div class="container1">

    <h1 class="topic">Viva Registrations</h1>

    <div class="border-rectangle">
        <?php if ($schedules):?>
            <?php foreach ($schedules as $schedule):?>
            <div class="schedule-item card">
                <div class="card-header">
                <h5 class="card-title"><?= htmlspecialchars($schedule['viva_name'])?></h5>
                </div>
                <div class="card-body">
                <table class="table">
                <tr>
                    <th>Module Code</th>
                    <td><?= htmlspecialchars($schedule['module_code'])?></td>
                    </tr>

                    <tr>
                    <th>Module Name</th>
                    <td><?= htmlspecialchars($schedule['module_name'])?></td>
                    </tr>

                    <tr>
                    <th>Date</th>
                    <td><?= htmlspecialchars($schedule['date'])?></td>
                    </tr>

                    <tr>
                    <th>Location</th>
                    <td><?= htmlspecialchars($schedule['location'])?></td>
                    </tr>
                    <tr>
                    
                </table>
                <?php if ($schedule['allow_submission']): ?>
                <form action="viva.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="module_name" value="<?= htmlspecialchars($schedule['module_name']) ?>">
                    <input type="hidden" name="exam_name" value="<?= htmlspecialchars($schedule['viva_name']) ?>">
                    <button type="submit" name="submit" class="btn btn-primary">Register</button>
                </form>
            <?php else: ?>
                <p>Registration closed for now.</p>
            <?php endif; ?>

                </div>
            </div>
            <?php endforeach;?>
        <?php else:?>
            <p>No Viva schedule found for <?= htmlspecialchars($course)?> <?= htmlspecialchars($batch_number)?>.</p>
        <?php endif;?>
    </div>
</div>

<br>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
