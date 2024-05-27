<?php
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username']; // Assuming you have stored the username in the session
    $book_id = $_POST['book_id'];

    $sql = "DELETE FROM reserved_books WHERE username = ? AND book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $username, $book_id);
    
    if ($stmt->execute()) {
        // Redirect back to the manageReservedBooks.php page after deletion
        header("Location: manageReservedBooks.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>
