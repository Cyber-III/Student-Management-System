<?php
session_start();
include_once('../../connection.php');

$username = $_SESSION['username'];
$batch_number = isset($_SESSION['batch_number']) ? $_SESSION['batch_number'] : null;

if ($batch_number === null) {
    die("Batch number is not set in the session.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['view']) && isset($_POST['assignment_name'])) {
        $assignment_name = $_POST['assignment_name'];
        
        // Fetch the file path from the database
        $sql = "SELECT file_path FROM assignments WHERE assignment_name = ? AND username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $assignment_name, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            if ($result->num_rows > 0) {
                $submission = $result->fetch_assoc();
                $file_path = $submission['file_path'];
                header("Location: upload_submission.php?message=viewed&file_path=" . urlencode($file_path));
                exit();
            } else {
                header("Location: upload_submission.php?message=nosub");
                exit();
            }
        } else {
            echo "Error preparing select statement: " . $conn->error;
        }
    } elseif (isset($_FILES['file']) && isset($_POST['assignment_name'])) {
        $module_name = $_POST['module_name'];
        $assignment_name = $_POST['assignment_name'];
        $module_code = $_POST['module_code'];
        $file = $_FILES['file'];
        $uploadDir = '../../ResultSection/Assignment/uploads/';
        $uploadFile = $uploadDir . basename($file['name']);

        // Check if the upload directory exists, if not create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Check if an entry already exists for the given assignment_name and username
            $sql = "SELECT * FROM assignments WHERE assignment_name = ? AND username = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ss', $assignment_name, $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    // Entry exists, update the file path
                    $sql = "UPDATE assignments SET file_path = ?, batch_number = ?, module_code = ?, module_name = ? WHERE assignment_name = ? AND username = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param('ssssss', $uploadFile, $batch_number, $module_code, $module_name, $assignment_name, $username);
                        if ($stmt->execute()) {
                            header("Location: upload_submission.php?message=updated");
                            exit();
                        } else {
                            echo "Error updating file path: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        echo "Error preparing update statement: " . $conn->error;
                    }
                } else {
                    // Entry does not exist, insert a new one
                    $sql = "INSERT INTO assignments (module_name, assignment_name, batch_number, username, file_path, module_code) VALUES (?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param('ssssss', $module_name, $assignment_name, $batch_number, $username, $uploadFile, $module_code);
                        if ($stmt->execute()) {
                            header("Location: upload_submission.php?message=submitted");
                            exit();
                        } else {
                            echo "Error inserting assignment: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        echo "Error preparing insert statement: " . $conn->error;
                    }
                }
            } else {
                echo "Error preparing select statement: " . $conn->error;
            }
        } else {
            header("Location: upload_submission.php?message=empsub");
            exit();
        }
    } else {
        echo "No file or assignment name provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
