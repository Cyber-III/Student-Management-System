<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['batch_no']) && isset($_POST['batch_course']) && isset($_POST['intake']) && isset($_POST['batch_uni'])) {
        // Handle batch creation
        $batch_number = htmlspecialchars($_POST['batch_no']);
        $course = htmlspecialchars($_POST['batch_course']);
        $intake = htmlspecialchars($_POST['intake']);
        $award_uni = htmlspecialchars($_POST['batch_uni']);

        $sql = "INSERT INTO batches (batch_no, course, intake, award_uni) 
                VALUES (?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $batch_number, $course, $intake, $award_uni);
            if ($stmt->execute()) {
                header("Location: studentSearch.php?message=insert");
                exit();
            } else {
                if ($stmt->errno == 1062) { // Duplicate entry error code
                    header("Location: studentSearch.php?message=exists");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } elseif (isset($_POST['batch_number'])) {
        // Handle batch deletion
        $batch_number = htmlspecialchars($_POST['batch_number']);

        $sql = "DELETE FROM batches WHERE batch_no = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $batch_number);
            if ($stmt->execute()) {
                header("Location: studentSearch.php?message=delete");
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
}
?>
