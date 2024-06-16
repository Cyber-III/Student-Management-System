<?php
include_once('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    // Debugging
    error_log("Updating user: $username with name: $name");

    $sql = "UPDATE team_members SET name='$name' WHERE username='$username'";
    if (mysqli_query($conn, $sql)) {
        header("Location: viva.php?message=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
