<?php
session_start();
ob_start();  // Start output buffering

include_once('../connection.php');
include_once('../assests/content/static/template.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: vacancies.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM vacancy_tbl WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$vacancy = $result->fetch_assoc();

if (!$vacancy) {
    header("Location: vacancies.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $vacancy['image']; // Initialize with the existing image data

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    $sql = "UPDATE vacancy_tbl SET title = ?, content = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssi', $title, $content, $image, $id);
        if ($stmt->execute()) {
            $_SESSION['edit_success'] = "Vacancy updated successfully.";
            header("Location: vacancytbl.php");
            exit();
        } else {
            $error = "Error updating vacancy: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error in SQL query: " . $conn->error;
    }
}

$stmt->close();
ob_end_flush();  // Flush output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vacancy</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-vacancy.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body class="body">
<div class="container">
    <h1>Edit Vacancy</h1>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($vacancy['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" required><?= htmlspecialchars($vacancy['content']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <p>Current image:</p>
            <img src="data:image/jpeg;base64,<?= base64_encode($vacancy['image']) ?>" alt="Current image" style="width: 100px; height: auto;">
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Update Vacancy</button>
        <a href="vacancytbl.php" class="btn btn-primary" style="background-color: red;">Back</a>
    </form>
</div>
</body>
</html>
