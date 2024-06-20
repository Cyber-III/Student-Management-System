<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM vacancy_tbl";
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
    <h1>Vacancies</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['content']) ?></td>
                <td>
                    <?php
                    if ($row['image']) {
                        $imageData = base64_encode($row['image']);
                        echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Image" style="width: 100px; height: auto;">';
                    }
                    ?>
                </td>
                <td>
                    <a href="edit_vacancy.php?id=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                    <a href="delete_vacancy.php?id=<?= $row['id'] ?>" class="btn btn-danger delete-button">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="add_vacancy.php" class="btn btn-success">Add New Vacancy</a>

</body>
</html>
