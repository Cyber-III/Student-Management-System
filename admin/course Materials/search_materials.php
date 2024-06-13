<?php
session_start();

// Include the database connection
include_once('../connection.php');

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session

// Handle search request
$search_batch = '';
if (isset($_GET['search'])) {
    $search_batch = $_GET['search_batch'];
}

// Fetch course materials filtered by batch name
$sql = "SELECT * FROM course_materials";
$params = [];
if ($search_batch) {
    $sql .= " WHERE batch_number LIKE ?";
    $params[] = '%' . $search_batch . '%';
}

$stmt = $conn->prepare($sql);
if ($stmt) {
    if ($params) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $modules = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $error = "Error in SQL query: " . $conn->error;
}

// Handle delete request
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM course_materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        header("Location: search_materials.php");
        exit();
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
    <title>Search Course Materials</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="courseMaterial-style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <div class="topic">
        <h1>Search Course Materials</h1>
    </div>

    <!-- Search Form -->
    <div class="search-container">
        <form method="get" action="search_materials.php">
            <div class="input-group mb-3">
                <input type="text" name="search_batch" class="form-control" placeholder="Search by Batch Name" value="<?php echo htmlspecialchars($search_batch); ?>">
                <button class="btn btn-primary" type="submit" name="search">Search</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($modules)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Module Name</th>
                        <th>Module Code</th>
                        <th>Topic</th>
                        <th>Batch Number</th>
                        <th>Course</th>
                        <th>Download</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                            <td><?php echo htmlspecialchars($module['module_code']); ?></td>
                            <td><?php echo htmlspecialchars($module['topic']); ?></td>
                            <td><?php echo htmlspecialchars($module['batch_number']); ?></td>
                            <td><?php echo htmlspecialchars($module['course']); ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($module['download']) ?>" class="view-link" target="_blank">Download</a>
                            </td>
                            <td>
                                <a href="edit_material.php?id=<?= htmlspecialchars($module['id']) ?>" class="btn btn-primary">Edit</a>
                            </td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($module['id']) ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <img src="Images/no_data.jpg" alt="No data available">
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
