<?php
session_start();
include_once('../connection.php');

// Fetch all vacancies
$vacancies = [];
$result = mysqli_query($conn, "SELECT * FROM vacancy_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $vacancies[] = $row;
}

// Display the success or error message if it exists
$success_message = '';
$error_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Remove the message from the session after displaying it
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Remove the message from the session after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vacancies</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Vacancies</h1>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <button onclick="document.getElementById('addVacancyModal').style.display='block'">Add Vacancy</button>

        <table>
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
                <?php foreach ($vacancies as $vacancy): ?>
                    <tr>
                        <td><?= htmlspecialchars($vacancy['id']) ?></td>
                        <td><?= htmlspecialchars($vacancy['title']) ?></td>
                        <td><?= htmlspecialchars($vacancy['content']) ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($vacancy['image']) ?>" alt="Image" width="100"></td>
                        <td>
                            <button onclick="editVacancy(<?= htmlspecialchars(json_encode($vacancy)) ?>)">Edit</button>
                            <form action="delete_vacancy.php" method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($vacancy['id']) ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Vacancy Modal -->
        <div id="addVacancyModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('addVacancyModal').style.display='none'">&times;</span>
                <h2>Add Vacancy</h2>
                <form action="add_vacancy.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" required>
                    </div>
                    <button type="submit">Add Vacancy</button>
                </form>
            </div>
        </div>

        <!-- Edit Vacancy Modal -->
        <div id="editVacancyModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('editVacancyModal').style.display='none'">&times;</span>
                <h2>Edit Vacancy</h2>
                <form action="edit_vacancy.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="edit-title">Title</label>
                        <input type="text" id="edit-title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-content">Content</label>
                        <textarea id="edit-content" name="content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-image">Image</label>
                        <input type="file" id="edit-image" name="image">
                    </div>
                    <button type="submit">Update Vacancy</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function editVacancy(vacancy) {
            document.getElementById('edit-id').value = vacancy.id;
            document.getElementById('edit-title').value = vacancy.title;
            document.getElementById('edit-content').value = vacancy.content;
            document.getElementById('editVacancyModal').style.display = 'block';
        }
    </script>
</body>
</html>
