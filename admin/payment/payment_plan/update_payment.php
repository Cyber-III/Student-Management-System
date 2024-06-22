<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $batch_number = $_POST['batch_number'];
    $no = $_POST['no'];
    $nxt_pay_date = $_POST['nxt_pay_date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $penalty = $_POST['penalty'];

    $query = "UPDATE make_payment_tbl SET 
                username='$username', 
                batch_number='$batch_number', 
                nxt_pay_date='$nxt_pay_date', 
                description='$description', 
                amount='$amount', 
                penalty='$penalty' 
              WHERE no='$no' and username='$username'";

    if (mysqli_query($conn, $query)) {
        header("Location: plan.php?message=updatedpay");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
