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

    // Prepare SQL insert statement for make_payment_tbl
    $sql = "INSERT INTO make_payment_tbl (username, batch_number, no, nxt_pay_date, description, amount) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Initialize prepared statement for make_payment_tbl
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssisss", $username, $batch_no, $paymentID, $date, $description, $amount);

        // Execute the statement
        if ($stmt->execute()) {
            // Prepare SQL update statement for payment_summary_tbl
            $update_sql = "UPDATE payment_summary_tbl 
                           SET tot_course_fee = tot_course_fee + ?, outstanding = outstanding + ?
                           WHERE username = ?";

            // Initialize prepared statement for payment_summary_tbl
            if ($update_stmt = $conn->prepare($update_sql)) {
                // Bind parameters
                $update_stmt->bind_param("dds", $amount, $amount, $username);

                // Execute the update statement
                if ($update_stmt->execute()) {
                    header("Location: plan.php?message=insertpayment");
                    exit();
                } else {
                    echo "Error updating payment summary: " . $update_stmt->error;
                }

                // Close the update statement
                $update_stmt->close();
            } else {
                echo "Error preparing update statement: " . $conn->error;
            }
        } else {
            echo "Error inserting payment: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing insert statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
