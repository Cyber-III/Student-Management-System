<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $sid = htmlspecialchars($_POST['sid']);
    $sname = htmlspecialchars($_POST['sname']);
    $password = htmlspecialchars($_POST['password']);
    $batch_number = htmlspecialchars($_POST['batch_number']);
    $course = htmlspecialchars($_POST['course']);
    $bdate = htmlspecialchars($_POST['bdate']);
    $nic = htmlspecialchars($_POST['nic']);
    $email = htmlspecialchars($_POST['email']);
    $contact = htmlspecialchars($_POST['contact']);
    $award_uni = htmlspecialchars($_POST['award_uni']);
    $uni_num = htmlspecialchars($_POST['uni_num']);
    $location = htmlspecialchars($_POST['location']);
    $gender = htmlspecialchars($_POST['gender']);

    // Prepare SQL insert statement
    $sql = "INSERT INTO login_tbl (student_name, username, password, course, batch_number, gender, dob, nic, email, contact, awarding_uni, uni_number, lec) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Initialize prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sssssssssssss", $sname, $sid, $password, $course, $batch_number, $gender, $bdate, $nic, $email, $contact, $award_uni, $uni_num, $location);
        
        // Execute the statement
        if ($stmt->execute()) {
            header("Location: studentSearch.php?message=insertstudent");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
