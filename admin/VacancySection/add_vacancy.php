<?php
session_start();

// Include the database connection
include_once('../connection.php');
include_once('../../admin/assests/content/static/template.php');

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session

// Initialize the success message
$success_message = "";

// Handle form submission for adding a new vacancy
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $image = null; // Set to null if no file is uploaded
    }

    $sql = "INSERT INTO vacancy_tbl (title, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sss', $title, $content, $image);
        if ($stmt->execute()) {
            // Set the success message
            $success_message = "Vacancy uploaded successfully.";
        } else {
            $error = "Error in SQL query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error in SQL query: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Vacancy</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-vacancy.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <h1>Add New Vacancy</h1>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <button type="submit" name="add" class="btn btn-primary">Add Vacancy</button>
        <a href="vacancytbl.php" class="btn btn-primary" style="background-color: red;">Back</a>
    </form>
</div>
</body>
</html>
