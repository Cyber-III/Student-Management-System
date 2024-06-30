<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    if (isset($_POST['course_name']) && isset($_POST['award_uni'])) {
        // Handle course insertion
        $course_name = htmlspecialchars($_POST['course_name']);
        $award_uni = htmlspecialchars($_POST['award_uni']);

        $sql = "INSERT INTO course_tbl (course_name, award_uni) VALUES (?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $course_name, $award_uni);
            if ($stmt->execute()) {
                header("Location: addCourse.php?message=insert");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
