<?php
include_once('../../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $id = $_POST['id'];
    $nxtPayDate = $_POST['nxtPayDate'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    if ($action == 'delete') {
        $sql = "DELETE FROM make_payment_tbl WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'update') {
        $sql = "UPDATE make_payment_tbl SET nxt_pay_date = ?, description = ?, amount = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $nxtPayDate, $description, $amount, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect back to the main page with a success message
    header("Location: plan.php?message=success");
    exit();
}

$conn->close();
?>
