<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database credentials
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "student_management_system";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $user = $_POST['username'];
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO recreation_mem (username, student_name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $student_name, $email);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to library_mem.php with success message
        header("Location: recreation_mem.php?success=true");
        exit();
    } else {
        // Error message
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request method.";
}
?>
