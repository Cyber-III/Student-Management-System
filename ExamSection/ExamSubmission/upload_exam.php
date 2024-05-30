<?php
session_start();
include_once('../../connection.php');

$username = $_SESSION['username']; // Get the username from the session
$batch_number = isset($_SESSION['batch_number']) ? $_SESSION['batch_number'] : null; // Get the batch number from the session

if ($batch_number === null) {
    die("Batch number is not set in the session.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['view']) && isset($_POST['exam_name'])) {
        $exam_name = $_POST['exam_name'];
        
        // Fetch the file path from the database
        $sql = "SELECT file_path FROM exam_submission WHERE exam_name = ? AND username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $exam_name, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            if ($result->num_rows > 0) {
                $submission = $result->fetch_assoc();
                $file_path = $submission['file_path'];
                header("Location: exam_submission.php?message=viewed&file_path=" . urlencode($file_path));
                exit();
            }
            
            else {
                header("Location: exam_submission.php?message=nosub"); //no submission message
                exit();
            }
        }
        
        else {
            echo "Error preparing select statement: " . $conn->error;
        }
    }
    
    elseif (isset($_FILES['file']) && isset($_POST['exam_name'])) {
        $exam_name = $_POST['exam_name'];
        $file = $_FILES['file'];
        $uploadDir = '../../ResultSection/Exam/uploads/';
        $uploadFile = $uploadDir . basename($file['name']);

        // Check if the upload directory exists, if not create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Check if an entry already exists for the given exam_name and username
            $sql = "SELECT * FROM exam_submission WHERE exam_name = ? AND username = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ss', $exam_name, $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    // Entry exists, update the file path
                    $sql = "UPDATE exam_submission SET file_path = ?, batch_number = ? WHERE exam_name = ? AND username = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param('ssss', $uploadFile, $batch_number, $exam_name, $username);
                        if ($stmt->execute()) {
                            header("Location: exam_submission.php?message=updated");
                            exit();
                        }
                        
                        else {
                            echo "Error updating file path: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                    
                    else {
                        echo "Error preparing update statement: " . $conn->error;
                    }
                }
                
                else {
                    // Entry does not exist, insert a new one
                    $sql = "INSERT INTO exam_submission (exam_name, batch_number, username, file_path) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param('ssss', $exam_name, $batch_number, $username, $uploadFile);
                        if ($stmt->execute()) {
                            header("Location: exam_submission.php?message=submitted");
                            exit();
                        }
                        
                        else {
                            echo "Error inserting file path: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                    
                    else {
                        echo "Error preparing insert statement: " . $conn->error;
                    }
                }
            }
            
            else {
                echo "Error preparing select statement: " . $conn->error;
            }
        }
        
        else {
            header("Location: exam_submission.php?message=empsub");
            exit();
        }
    }
    
    else {
        echo "No file or exam name provided.";
    }
}

else {
    echo "Invalid request method.";
}

?>
