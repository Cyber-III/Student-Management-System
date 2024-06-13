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
    <link rel="stylesheet" href="style-examAdmission.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <style>
        /* Hide the logo by default */
        .campus-logo {
            display: none;
        }

        /* Display the logo only in print */
        @media print {
            .campus-logo {
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }
            body {
                visibility: hidden;
            }
            #table {
                visibility: visible;
                width: 100%;
            }
            #table thead th {
                text-align: center;
            }
        }
    </style>

</head>
<body class="one">
    <div class="container1">
    <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">Exam Admission</h1>
        <div class="border-rectangle">
            <?php if ($schedules): ?>
                <?php foreach ($schedules as $index => $schedule): ?>
                <div class="Rectangle">
                    <div class="campus-logo">
                        <img id="image-<?= $index ?>" src="./pics/L3.png" alt="Campus Logo">
                    </div>
                    <button id="print-button-<?= $index ?>" type="button" class="print btn btn-primary mb-3">Print</button>
                    <div id="content-<?= $index ?>">
                        <table id="table" class="table">
                            <thead>
                                <tr>
                                    <th colspan="2" scope="col">Exam Admission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Student Name</th>
                                    <td><span><?php echo isset($student_name) ? $student_name : ''; ?></span></td>
                                </tr>
                                <tr>
                                    <th scope="row">Student ID</th>
                                    <td><span><?php echo isset($username) ? $username : ''; ?></span></td>
                                </tr>
                                <tr>
                                    <th scope="row">Exam Name</th>
                                    <td><span class="form-value"><?= htmlspecialchars($schedule['exam_name']) ?></span></td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td><span class="form-value"><?= htmlspecialchars($schedule['date']) ?></span></td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td><span class="form-value"><?= htmlspecialchars($schedule['time']) ?></span></td>
                                </tr>
                                <tr>
                                    <th scope="row">Location</th>
                                    <td><span class="form-value"><?= htmlspecialchars($schedule['location']) ?></span></td>
                                </tr>
                                <tr>
                                    <th scope="row">Hours</th>
                                    <td><span><?= htmlspecialchars($schedule['hours']) ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    document.getElementById('print-button-<?= $index ?>').addEventListener('click', function () {
                        const content = document.getElementById('content-<?= $index ?>');
                        const image = document.getElementById('image-<?= $index ?>').outerHTML;
                        const combinedContent = image + content.outerHTML;

                        html2pdf().from(combinedContent).save('exam_admission_<?= $index ?>.pdf');
                    });
                </script>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No assignment schedule found for <?= htmlspecialchars($course) ?> <?= htmlspecialchars($batch_number) ?>.</p>
            <?php endif; ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
