<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM vacancy_apply_tbl";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacancies Table</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-vacancy.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    if (!confirm('Are you sure you want to delete this vacancy?')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
</head>
<body class="body">
    <h1>Applications</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Job ID</th>
                <th>Job Title</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Batch</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Gender</th>

            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['job_id']) ?></td>
                <td><?= htmlspecialchars($row['job_title']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['batch_num']) ?></td>
                <td><?= htmlspecialchars($row['contact']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['gender']) ?></td>
                
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
