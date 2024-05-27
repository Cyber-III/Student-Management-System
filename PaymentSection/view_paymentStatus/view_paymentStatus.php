<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');
include_once('../../assests/content/static/template.php');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the table
$username = $_SESSION['username']; // Assuming you have stored the username in the session
$sql = "SELECT * FROM payment_status WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-view_paymentStatus.css"> <!--Relevant PHP File CSS-->

</head>
<body>
<div class="table">
        <table>
            <tr>
                <th>No</th>
                <th>Paid Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Payment Link</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $amount = $row['amount'];
                    $record_id = $row['no']; // Use record id to uniquely identify the record
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['paid_date']) . "</td>";
                    echo "<td> Course Fee </td>";
                    echo "<td>" . htmlspecialchars($amount) . "</td>";
                    echo '<td> <button type="submit" class="view-link">PRINT</button> </td>';
                    echo "</tr>";
                }
            } 
            else {
                echo "<tr><td colspan='5'>No payments found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>