<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Database credentials
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "student_management_system";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data from the query parameters
    $user = $_GET['username'];
    $student_name = $_GET['student_name'];
    $email = $_GET['email'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO recreation_mem (username, student_name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $student_name, $email);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: recreation_mem.php?success=true");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
