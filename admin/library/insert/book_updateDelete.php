<?php
session_start();

include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure action is set and valid
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'edit':
                handleEdit();
                break;
            case 'delete':
                handleDelete();
                break;
            default:
                // Handle invalid action
                break;
        }
    }
}

function handleEdit() {
    global $conn;

    // Validate inputs
    $book_id = $_POST['book_id'];
    $book_name = mysqli_real_escape_string($conn, $_POST['book_name']);
    $author_name = mysqli_real_escape_string($conn, $_POST['author_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Check if photo is uploaded
    if ($_FILES['photo']['size'] > 0) {
        // Process uploaded photo
        $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
        $query = "UPDATE books SET book_name='$book_name', author_name='$author_name', category='$category', image='$photo' WHERE id='$book_id'";
    } else {
        // Update without changing photo
        $query = "UPDATE books SET book_name='$book_name', author_name='$author_name', category='$category' WHERE id='$book_id'";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: insertBooks.php?message=updated");
        exit();
    } else {
        echo "Error updating book: " . mysqli_error($conn);
    }
}

function handleDelete() {
    global $conn;

    $book_id = $_POST['book_id'];

    $query = "DELETE FROM books WHERE id='$book_id'";

    if (mysqli_query($conn, $query)) {
        header("Location: insertBooks.php?message=delete");
        exit();
    } else {
        echo "Error deleting book: " . mysqli_error($conn);
    }
}
?>
