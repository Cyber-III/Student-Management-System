<?php
// Include the database connection
include_once('../connection.php');

if (isset($_GET['batch_number'])) {
    $batch_number = htmlspecialchars($_GET['batch_number']);
    $result = mysqli_query($conn, "SELECT * FROM login_tbl WHERE batch_number = '$batch_number'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td data-cell='ST_Name'>{$row['student_name']}</td>
                    <td data-cell='Username'>{$row['username']}</td>
                    <td data-cell='Course'>{$row['course']}</td>
                    <td data-cell='Batch_No'>{$row['batch_number']}</td>
                    <td data-cell='Gender'>{$row['gender']}</td>
                    <td data-cell='DOB'>{$row['dob']}</td>
                    <td data-cell='NIC'>{$row['nic']}</td>
                    <td data-cell='Contact'>{$row['contact']}</td>
                  </tr>";
        }
    }
} elseif (isset($_GET['username'])) {
    $username = htmlspecialchars($_GET['username']);
    $result = mysqli_query($conn, "SELECT * FROM login_tbl WHERE username LIKE '%$username%'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td data-cell='Username'>{$row['username']}</td>
                    <td data-cell='Course'>{$row['course']}</td>
                    <td data-cell='Batch_No'>{$row['batch_number']}</td>
                    <td data-cell='Gender'>{$row['gender']}</td>
                    <td data-cell='DOB'>{$row['dob']}</td>
                    <td data-cell='NIC'>{$row['nic']}</td>
                    <td data-cell='ST_Name'>{$row['student_name']}</td>
                    <td data-cell='Contact'>{$row['contact']}</td>
                  </tr>";
        }
    }
}
?>
