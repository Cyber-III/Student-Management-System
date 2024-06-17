<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['usernames']);
    $batch_no = htmlspecialchars($_POST['batch_no']);
    $paymentID = htmlspecialchars($_POST['paymentID']);
    $date = htmlspecialchars($_POST['date']);
    $description = htmlspecialchars($_POST['description']);
    $amount = htmlspecialchars($_POST['amount']);

    // Prepare SQL insert statement
    $sql = "INSERT INTO make_payment_tbl (username, batch_number, no, nxt_pay_date, description, amount) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Initialize prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssisss", $username, $batch_no, $paymentID, $date, $description, $amount);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: plan.php?message=insertpayment");
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
