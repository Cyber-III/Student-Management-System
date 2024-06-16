<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if book_id and username are provided via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id']) && isset($_POST['username'])) {
    $bookId = $_POST['book_id'];
    $username = $_POST['username'];
    
    // Prepare SQL statement to delete from reserved_books table
    $sql = "DELETE FROM reserved_books WHERE book_id = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    
    // Check if the statement was prepared correctly
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }

    // Bind parameters
    $stmt->bind_param("ss", $bookId, $username);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Return success message if deletion was successful
        echo "Record deleted successfully";
    } else {
        // Return error message if deletion failed
        echo "Error deleting record: (" . $stmt->errno . ") " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Return error for invalid requests
    http_response_code(400);
    echo "Invalid request";
}

// Close the database connection
$conn->close();
?>
