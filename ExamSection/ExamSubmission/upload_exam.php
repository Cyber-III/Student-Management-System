<?php
session_start();
include_once('../../connection.php');

$username = $_SESSION['username'];
$batch_number = isset($_SESSION['batch_number']) ? $_SESSION['batch_number'] : null;

if ($batch_number === null) {
    die("Batch number is not set in the session.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['view']) && isset($_POST['exam_name'])) {
        viewSubmission($conn, $username, $_POST['exam_name']);
    } elseif (isset($_FILES['file']) && isset($_POST['exam_name']) && isset($_POST['module_name']) && isset($_POST['module_code'])) {
        handleFileUpload($conn, $username, $batch_number, $_POST['exam_name'], $_POST['module_name'], $_POST['module_code'], $_FILES['file']);
    } else {
        echo "No file or exam name provided.";
    }
} else {
    echo "Invalid request method.";
}

function viewSubmission($conn, $username, $exam_name) {
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
        } else {
            header("Location: exam_submission.php?message=nosub");
            exit();
        }
    } else {
        echo "Error preparing select statement: " . $conn->error;
    }
}

function handleFileUpload($conn, $username, $batch_number, $exam_name, $module_name, $module_code, $file) {
    $uploadDir = '../../ResultSection/Exam/uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    // Check if the upload directory exists, if not create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file to the upload directory
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        if (submissionExists($conn, $exam_name, $username)) {
            updateSubmission($conn, $uploadFile, $batch_number, $exam_name, $username);
        } else {
            insertSubmission($conn, $module_code, $module_name, $exam_name, $batch_number, $username, $uploadFile);
        }
    } else {
        header("Location: exam_submission.php?message=empsub");
        exit();
    }
}

function submissionExists($conn, $exam_name, $username) {
    $sql = "SELECT * FROM exam_submission WHERE exam_name = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ss', $exam_name, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows > 0;
    } else {
        echo "Error preparing select statement: " . $conn->error;
        return false;
    }
}

function updateSubmission($conn, $uploadFile, $batch_number, $exam_name, $username) {
    $sql = "UPDATE exam_submission SET file_path = ?, batch_number = ? WHERE exam_name = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssss', $uploadFile, $batch_number, $exam_name, $username);
        if ($stmt->execute()) {
            header("Location: exam_submission.php?message=updated");
            exit();
        } else {
            echo "Error updating file path: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }
}

function insertSubmission($conn, $module_code, $module_name, $exam_name, $batch_number, $username, $uploadFile) {
    $sql1 = "INSERT INTO exam_submission (module_code, module_name, exam_name, batch_number, username, file_path) VALUES (?, ?, ?, ?, ?, ?)";
    $sql3 = "INSERT INTO assignments (username, batch_number, module_name, module_code, assignment_name, file_path) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt1 = $conn->prepare($sql1);
    $stmt3 = $conn->prepare($sql3);

    if ($stmt1 && $stmt3) {
        $conn->begin_transaction();
        try {
            $stmt1->bind_param('ssssss', $module_code, $module_name, $exam_name, $batch_number, $username, $uploadFile);
            $stmt3->bind_param('ssssss', $username, $batch_number, $module_name, $module_code, $exam_name, $uploadFile);

            $stmt1->execute();
            $stmt3->execute();

            $conn->commit();
            header("Location: exam_submission.php?message=submitted");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error inserting file path: " . $e->getMessage();
        }

        $stmt1->close();
        $stmt3->close();
    } else {
        echo "Error preparing insert statements: " . $conn->error;
    }
}
?>
