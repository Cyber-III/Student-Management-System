<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Handle deletion message
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch all data from the reserved_books table
$books_data = [];
$result = mysqli_query($conn, "SELECT * FROM reserved_books");
while ($row = mysqli_fetch_assoc($result)) {
    $books_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Orders</title>
    
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-bookOrders.css">
</head>
<body>
    <?php if ($message == 'delete'): ?>
        <div class="alert alert-success">Record was deleted successfully.</div>
    <?php endif; ?>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Photo</th>
                    <th>Reserved Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="library-tbody">
                <?php if ($result->num_rows > 0): ?>
                    <?php foreach ($books_data as $row): ?>
                        <tr data-book-id="<?= htmlspecialchars($row['book_id']) ?>" data-username="<?= htmlspecialchars($row['username']) ?>">
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['book_id']) ?></td>
                            <td><?= htmlspecialchars($row['book_name']) ?></td>
                            <td>
                                <?php if (!empty($row['book_image'])): ?>
                                    <?php
                                    $imgData = base64_encode($row['book_image']);
                                    $src = 'data:image/jpeg;base64,' . $imgData;
                                    ?>
                                    <img src="<?= $src ?>" alt="Book Image" width="100" height="100">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['reserved_date']) ?></td>
                            <td>
                                <button onclick="confirmDelete(this)" class="manage-button delete-link">Done</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(button) {
            var row = button.parentNode.parentNode;
            var bookId = row.getAttribute('data-book-id');
            var username = row.getAttribute('data-username');
            
            // Ask for confirmation
            if (confirm("Are you sure you handed over this book?")) {
                // AJAX request to delete the record
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_book.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // On success, remove the row from the table
                        if (xhr.responseText === "Record deleted successfully") {
                            row.parentNode.removeChild(row);
                            // Optionally, you can reload the page to update the message
                            window.location.href = 'bookOrders.php?message=delete';
                        } else {
                            alert("Error: " + xhr.responseText);
                        }
                    }
                };
                xhr.send("book_id=" + encodeURIComponent(bookId) + "&username=" + encodeURIComponent(username));
            }
        }
    </script>

</body>
</html>
