<?php
include_once('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    $sql = "DELETE FROM team_members WHERE username='$username'";
    if (mysqli_query($conn, $sql)) {
        header("Location: viva.php?message=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
