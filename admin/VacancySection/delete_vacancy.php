<?php
session_start();
include_once('../connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: vacancytbl.php");
    exit();
}

$id = $_GET['id'];

// Start a transaction
$conn->begin_transaction();

try {
    // Delete from vacancy_tbl
    $sql1 = "DELETE FROM vacancy_tbl WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('i', $id);
    $stmt1->execute();
    $stmt1->close();

    // Delete from vacancy_apply_tbl
    $sql2 = "DELETE FROM vacancy_apply_tbl WHERE job_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $stmt2->close();

    // Commit the transaction
    $conn->commit();

    $_SESSION['delete_success'] = "Vacancy deleted successfully.";
} catch (Exception $e) {
    // Rollback the transaction if something goes wrong
    $conn->rollback();
    $_SESSION['delete_error'] = "Error deleting vacancy: " . $e->getMessage();
}

header("Location: vacancytbl.php");
exit();
?>
