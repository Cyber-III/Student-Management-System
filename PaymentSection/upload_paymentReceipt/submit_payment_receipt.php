<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $payment_date = $_POST['payment_date'];
    $payment_amount = $_POST['payment_amount'];
    $remark = $_POST['remark'];

    // File upload handling
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['file']['name']);
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            // Insert data into the database
            $sql = "INSERT INTO payment_receipts (student_id, student_name, payment_date, payment_amount, file_path, remark) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssdss', $student_id, $student_name, $payment_date, $payment_amount, $file_path, $remark);

            if ($stmt->execute()) {
                // Redirect with success flag
                header("Location: upload_paymentReceipt.php?success=1");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }

    $conn->close();
}
?>
