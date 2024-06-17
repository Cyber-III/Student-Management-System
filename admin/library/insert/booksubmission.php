<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $bname = htmlspecialchars($_POST['bname']);
    $aname = htmlspecialchars($_POST['aname']);
    $category = htmlspecialchars($_POST['category']);

    // Check if a file is uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Get the uploaded file info
        $image = $_FILES['photo']['tmp_name'];
        $imgData = file_get_contents($image);

        // Prepare SQL insert statement
        $sql = "INSERT INTO books (book_name, author_name, category, image) 
                VALUES (?, ?, ?, ?)";

        // Initialize prepared statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters (note 'b' for blob data type)
            $null = NULL; // To bind the blob correctly
            $stmt->bind_param("sssb", $bname, $aname, $category, $null);
            $stmt->send_long_data(3, $imgData);

            // Execute the statement
            if ($stmt->execute()) {
                header("Location: insertBooks.php?message=insertbook");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Error uploading image";
    }

    // Close the database connection
    $conn->close();
}
?>
