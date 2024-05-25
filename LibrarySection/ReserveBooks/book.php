<?php
session_start();

include_once('../../connection.php');

$username = $_SESSION['username'];
$book_id = $_POST['book_id'];
$book_name = $_POST['book_name'];
$book_image = base64_decode($_POST['book_image']);

// Insert reservation data into reserved_books table
$sql = "INSERT INTO reserved_books (username, book_id, book_name, book_image) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

// Use 'siss' if book_id is integer and book_image is string. If book_image is a binary type, use 'b' for it.
$stmt->bind_param('siss', $username, $book_id, $book_name, $book_image);

// Print data to debug
error_log("Username: $username");
error_log("Book ID: $book_id");
error_log("Book Name: $book_name");
error_log("Book Image: " . base64_encode($book_image));

if ($stmt->execute()) {
    echo "Reservation successful";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
