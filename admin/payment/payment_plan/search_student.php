<?php
// Include the database connection
include_once('../../connection.php');

if (isset($_GET['batch_number'])) {
    $batch_number = htmlspecialchars($_GET['batch_number']);
    $result = mysqli_query($conn, "SELECT * FROM make_payment_tbl WHERE batch_number = '$batch_number'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['no']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['nxt_pay_date']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['penalty']}</td>
                    <td><button class='manage-btn view-link'>Manage</button></td>
                  </tr>";
        }
    }
} elseif (isset($_GET['username'])) {
    $username = htmlspecialchars($_GET['username']);
    $result = mysqli_query($conn, "SELECT * FROM make_payment_tbl WHERE username LIKE '%$username%'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['no']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['nxt_pay_date']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['penalty']}</td>
                    <td><button class='manage-btn view-link'>Manage</button></td>
                  </tr>";
        }
    }
}
?>
