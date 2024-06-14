<?php
// Include the database connection
include_once('../connection.php');

if (isset($_GET['batch_number'])) {
    $batch_number = htmlspecialchars($_GET['batch_number']);
    $result = mysqli_query($conn, "SELECT * FROM login_tbl WHERE batch_number = '$batch_number'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['student_name']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['batch_number']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['dob']}</td>
                    <td>{$row['nic']}</td>
                    <td>{$row['contact']}</td>
                  </tr>";
        }
    }
} elseif (isset($_GET['username'])) {
    $username = htmlspecialchars($_GET['username']);
    $result = mysqli_query($conn, "SELECT * FROM login_tbl WHERE username LIKE '%$username%'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['student_name']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['batch_number']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['dob']}</td>
                    <td>{$row['nic']}</td>
                    <td>{$row['contact']}</td>
                  </tr>";
        }
    }
}
?>
