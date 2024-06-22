<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no = $_POST['no'];
    $username = $_POST['username'];

    $query = "DELETE FROM make_payment_tbl WHERE no='$no' and username='$username'";

    if (mysqli_query($conn, $query)) {
        header("Location: plan.php?message=delete");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
