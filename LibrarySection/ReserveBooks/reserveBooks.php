<?php

session_start();

include_once('../../connection.php');
include_once('../../assests/content/static/template.php');

$username = $_SESSION['username'];

$sql = "SELECT status FROM library_mem WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $user['status'] === 'active') {
    $book_name = isset($_GET['book_name']) ? $_GET['book_name'] : '';
    $author_name = isset($_GET['author_name']) ? $_GET['author_name'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    $sql = "SELECT * FROM books WHERE book_name LIKE ? AND author_name LIKE ? AND category LIKE ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $book_name = '%' . $book_name . '%';
    $author_name = '%' . $author_name . '%';
    $category = '%' . $category . '%';
    $stmt->bind_param('sss', $book_name, $author_name, $category);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
        <link rel="stylesheet" href="style-reserveBooks.css"> <!--Relevant PHP File CSS-->

    </head>
    <body class="first">
    <div class="search-form">
        <form method="GET" action="">
        <div class="flex-container">
            <label for="book_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Book Name</label>
            <input type="text" id="book_name" name="book_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Book name" value="">
        </div>

        <div class="flex-container">
            <label for="author_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Author Name</label>
            <input type="text" id="author_name" name="author_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Author name" value="">
        </div>

        <div class="flex-container">
            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
            <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select Category</option>
                <option value="Software Engineering">Software Engineering</option>
                <option value="Automobile">Automobile</option>
            </select>
        </div>

        <button type="submit" value="Search" class="view-link">Search</button>
        </form>
    </div>

    <br> <br>
        <div class="container">
        <div class="table-container-2">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Author Name</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Book Now</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td data-label='Book ID'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td data-label='Book Name'>" . htmlspecialchars($row['book_name']) . "</td>";
                                echo "<td data-label='Author Name'>" . htmlspecialchars($row['author_name']) . "</td>";
                                echo "<td data-label='Category'>" . htmlspecialchars($row['category']) . "</td>";
                                echo '<td data-label="Image"><img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" width="100" height="100"/></td>';
                                echo '<td data-label="Book Now" class="text-center"><button onclick="confirmBooking(' . htmlspecialchars($row['id']) . ', \'' . htmlspecialchars($row['book_name']) . '\', \'' . base64_encode($row['image']) . '\')" class="view-link">Book</button></td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No books found</td></tr>";
                        }
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>

    </body>

    <script>
    function confirmBooking(id, book_name, image) {
        if (confirm("Are you sure to book this book?")) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Book has been reserved successfully.");
                    } else {
                        alert("Error: " + xhr.responseText);
                    }
                }
            };
            var url = "book.php";
            var params = "book_id=" + id + "&book_name=" + encodeURIComponent(book_name) + "&book_image=" + encodeURIComponent(image);
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(params);
        }
    }

    </script>



    </html>

    <?php
} else {
    // User is not active or does not exist
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
        <link rel="stylesheet" href="style-reserveBooks.css"> <!--Relevant PHP File CSS-->
    </head>
    <body class="second">
        <div class="message">
            <p>You are still not registered for library membership... After registration and your status is activated, you can log in to the library.</p>
        </div>
    </body>
    </html>
    <?php
}
?>
