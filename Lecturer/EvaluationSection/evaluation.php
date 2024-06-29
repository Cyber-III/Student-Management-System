<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Get the current lecturer's username from the session
$current_username = $_SESSION['username'];

// Fetch the current lecturer's name
$sql = "SELECT name FROM lecturers WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_username);
$stmt->execute();
$stmt->bind_result($lecturer_name);
$stmt->fetch();
$stmt->close();

// Check if a delete request is made
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM lecture_evaluation WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "Record deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all evaluation records for the current lecturer
$sql = "SELECT * FROM lecture_evaluation WHERE lec_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $lecturer_name);
$stmt->execute();
$evaluations = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-evaluation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Admin Lecture Evaluation</title>
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row mt-5">
            <div class="col-12">
                <h2>Lecture Evaluations</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Lecturer Name</th>
                            <!-- <th>Username</th> -->
                            <th>Title</th>
                            <th>Evaluation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($evaluations->num_rows > 0): ?>
                            <?php while ($row = $evaluations->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['lec_name']) ?></td>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['evaluation']) ?></td>
                                    <td>
                                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
