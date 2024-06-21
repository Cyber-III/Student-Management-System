<?php
// Include the database connection
include_once('../../connection.php');

if (isset($_GET['batch_number'])) {
    $batch_number = htmlspecialchars($_GET['batch_number']);
    $result = mysqli_query($conn, "SELECT * FROM make_payment_tbl WHERE batch_number = '$batch_number'");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td data-cell = 'No.'>{$row['no']}</td>
                    <td data-cell = 'Username'>{$row['username']}</td>
                    <td data-cell = 'Next Payment Date'>{$row['nxt_pay_date']}</td>
                    <td data-cell = 'Description'>{$row['description']}</td>
                    <td data-cell = 'Amount'>{$row['amount']}</td>
                    <td data-cell = 'Penalty'>{$row['penalty']}</td>
                    <td data-cell = 'Action'><button class='manage-btn view-link'>Manage</button></td>
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
