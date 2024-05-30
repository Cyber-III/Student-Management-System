<?php
session_start();
include_once('../../connection.php');
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

        // Fetch the assignments based on the batch number
        $sql = "SELECT id, username, batch_number, module_name, results, mitigation_request, file_path FROM assignments WHERE batch_number = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $batch_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $assignments = $result->fetch_all(MYSQLI_ASSOC); // Fetch all records
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
    <title>Assignment Results</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-results.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <h1 class="topic">Assignment Results</h1>
    <div class="table-responsive">
        <?php if ($assignments): ?>
           <div class="table-container-1">
                <div class="table"> 
                        <table>
                            <thead class="table-dark">
                                <tr>
                                    <th>Username</th>
                                    <th>Batch Number</th>
                                    <th>Module Name</th>
                                    <th>Results</th>
                                    <th>Mitigation Request</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($assignment['username']) ?></td>
                                        <td><?= htmlspecialchars($assignment['batch_number']) ?></td>
                                        <td><?= htmlspecialchars($assignment['module_name']) ?></td>
                                        <td><?= htmlspecialchars($assignment['results']) ?></td>
                                        <td><?= htmlspecialchars($assignment['mitigation_request']) ?></td>
                                        <td><a href="<?= htmlspecialchars($assignment['file_path']) ?>" target="_blank" class="btn btn-info">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>    
                    <?php else: ?>
                        <p>No assignments found for batch <?= htmlspecialchars($batch_number) ?>.</p>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
