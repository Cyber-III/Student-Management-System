<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

include_once('../assests/content/static/template.php');

// Penalty configuration
$penalty_per_day = 100;
$current_date = new DateTime(); // Get the current date

// Query to get all payment records
$sql = "SELECT * FROM make_payment_tbl WHERE username = ?";
$stmt = $conn->prepare($sql);
$username = $_SESSION['username']; // Assuming you have stored the username in the session
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
    <title>Penalty Page</title>
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style.css"> <!--Relevant PHP File CSS-->
</head>
<body>
    
    <div class="table">
        <table>
            <tr>
                <th>No</th>
                <th>Next Payment Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Penalty</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nxt_pay_date = new DateTime($row['nxt_pay_date']);
                    $interval = $nxt_pay_date->diff($current_date);
                    $days_overdue = $interval->days;
                    $penalty = 0;

                    if ($interval->invert == 0 && $days_overdue > 30) { // Check if the payment is overdue more than 30 days
                        $days_overdue -= 30; // Only charge penalty for days after the 30-day grace period
                        $penalty = $days_overdue * $penalty_per_day;
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nxt_pay_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                    echo "<td>Rs. " . htmlspecialchars($penalty) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No payments found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
    
</body>
</html>
