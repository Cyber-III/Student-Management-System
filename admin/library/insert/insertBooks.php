<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Fetch all data from the books table
$books_data = [];
$result = mysqli_query($conn, "SELECT * FROM books");
while ($row = mysqli_fetch_assoc($result)) {
    $books_data[] = $row;
}

// Fetch awarding batch numbers
$batch_numbers = [];
$result2 = mysqli_query($conn, "SELECT DISTINCT book_name FROM books");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['book_name'];
}

// Handle messages
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-insertBooks.css">

    <script>
        function searchByBookName() {
            var searchQuery = document.getElementById("search_book_name").value.toLowerCase();
            var rows = document.querySelectorAll("#library-tbody tr");
            rows.forEach(row => {
                var bookName = row.children[0].textContent.toLowerCase();
                if (bookName.includes(searchQuery)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function searchByCategory() {
            var selectedCategory = document.getElementById("search_category").value;
            var rows = document.querySelectorAll("#library-tbody tr");
            rows.forEach(row => {
                var category = row.children[2].textContent;
                if (selectedCategory === "" || category === selectedCategory) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }


        function manageBook(row) {
            const cells = row.querySelectorAll('td');
            document.getElementById('modal').style.display = 'block';
            document.getElementById('manage-book_name').value = cells[0].textContent;
            document.getElementById('manage-author_name').value = cells[1].textContent;
            document.getElementById('manage-category').value = cells[2].textContent;
            document.getElementById('manage-current-photo').src = cells[3].querySelector('img') ? cells[3].querySelector('img').src : '';
            document.getElementById('manage-book_id').value = row.dataset.bookId;  // Assuming each row has a data-book-id attribute
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Attach the event listener to the buttons after the rows are created
        document.querySelectorAll('.manage-button').forEach(button => {
            button.addEventListener('click', function() {
                manageBook(this.closest('tr'));
            });
        });

        document.querySelector('.close').addEventListener('click', closeModal);
    </script>


</head>
<body>

<div class="main">
    <?php if ($message == 'insertbook'): ?>
        <div class="alert alert-success">Book was inserted successfully.</div>
    <?php elseif ($message == 'updated'): ?>
        <div class="alert alert-success">The Records was updated successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">Book was deleted successfully.</div>
    <?php endif; ?>
<form action="booksubmission.php" method="POST" enctype="multipart/form-data">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="bname">Book Name: </label>
                    <input type="text" id="bname" name="bname" required>
                </div>
                <div class="form-group">
                    <label for="aname">Author Name: </label>
                    <input type="text" id="aname" name="aname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Software Engineering">Software Engineering</option>
                        <option value="Automobile">Automobile</option>
                        <option value="Automobile">Management</option>
                        <option value="Automobile">Strategic Management</option>
                        <option value="Automobile">Marketing</option>
                        <option value="Automobile">Economics</option>
                        <option value="Automobile">Research</option>
                        <option value="Automobile">Accounting</option>
                        <option value="Automobile">Finance</option>
                        <option value="Automobile">Statistics</option>
                        <option value="Automobile">Psychology</option>
                        <option value="Automobile">Nursing</option>
                        <option value="Automobile">Law</option>
                        <option value="Automobile">Quantity Survey</option>

                        
                        <?php foreach ($award_unis as $award_uni): ?>
                            <option value="<?= htmlspecialchars($award_uni) ?>"><?= htmlspecialchars($award_uni) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="photo">Photo: </label>
                    <input type="file" id="photo" name="photo" required>
                </div>
            </div>
            
            <button type="submit" class="view-link">Submit</button>
            
        </div>
        <br>
    </form>
    <br>

    <!-- Search bar -->
    <div class="form-row">
        <div class="form-group">
            <label for="search_book_name">Search by Book Name:</label>
            <div class="input-group">
                <input type="text" id="search_book_name" name="search_book_name" onkeyup="searchByBookName()">
            </div>
        </div>
        <div class="form-group">
            <label for="search_category">Search by Category:</label>
            <div class="input-group">
                <select id="search_category" name="search_category" onchange="searchByCategory()">
                    <option value="">Choose Category</option>
                    <option value="Software Engineering">Software Engineering</option>
                    <option value="Automobile">Automobile</option>
                    <option value="Management">Management</option>
                    <option value="Strategic Management">Strategic Management</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Economics">Economics</option>
                    <option value="Research">Research</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Finance">Finance</option>
                    <option value="Statistics">Statistics</option>
                    <option value="Psychology">Psychology</option>
                    <option value="Nursing">Nursing</option>
                    <option value="Law">Law</option>
                    <option value="Quantity Survey">Quantity Survey</option>
                </select>
            </div>
        </div>
    </div>
    <br>

    <div class="table_container">
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Category</th>
                    <th>Photo</th>
                </tr>
            </thead>
            <tbody id="library-tbody">
                <?php if ($result->num_rows > 0): ?>
                    <?php foreach ($books_data as $row): ?>
                        <tr data-book-id="<?= htmlspecialchars($row['id']) ?>">
                            <td data-cell ="Book Name"><?= htmlspecialchars($row['book_name']) ?></td>
                            <td data-cell ="Author Name"><?= htmlspecialchars($row['author_name']) ?></td>
                            <td data-cell ="Category"><?= htmlspecialchars($row['category']) ?></td>
                            <td data-cell="Photo">
                                <?php if (!empty($row['image'])): ?>
                                    <?php
                                    $imgData = base64_encode($row['image']);
                                    $src = 'data:image/jpeg;base64,' . $imgData;
                                    ?>
                                    <img src="<?= $src ?>" alt="Book Image" width="100" height="100">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><button onclick="manageBook(this.parentNode.parentNode)" class="manage-button view-link">Manage</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            
            <h2>Edit Book Information</h2>
            
            <form action="book_updateDelete.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="book_id" id="manage-book_id">
                
                <div class="form-group">
                    <label for="manage-book_name">Book Name:</label>
                    <input type="text" id="manage-book_name" name="book_name" required>
                </div>
                <div class="form-group">
                    <label for="manage-author_name">Author Name:</label>
                    <input type="text" id="manage-author_name" name="author_name" required>
                </div>
                <div class="form-group">
                    <label for="manage-category">Category:</label>
                    <select id="manage-category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Software Engineering">Software Engineering</option>
                        <option value="Automobile">Automobile</option>
                        <!-- Add more categories as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="manage-photo">Photo:</label>
                    <input type="file" id="manage-photo" name="photo">
                </div>
                <div class="form-group">
                    <label>Current Photo:</label>
                    <img id="manage-current-photo" src="" alt="Book Image" width="100" height="100">
                </div>
                <br>
                <div class="form-group">
                    <div class="button-container">
                        <button type="submit" name="action" value="edit" class="view-link">Edit</button>
                        <button type="submit" name="action" value="delete" class="delete-link">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    

</body>

</html>
