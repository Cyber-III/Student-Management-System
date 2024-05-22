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

// Handle successful payment
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $username = $_GET['username'];
    $record_id = $_GET['record_id'];
    $amount = $_GET['amount'];

    // Remove the record from make_payment_tbl for the specific username
    $delete_sql = "DELETE FROM make_payment_tbl WHERE username = ? AND no = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('si', $username, $record_id);
    $stmt->execute();

    // Update the payment_summary_tbl
    $update_sql = "UPDATE payment_summary_tbl SET amount_paid = amount_paid + ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('is', $amount, $username);
    $stmt->execute();

    $update_sql1 = "UPDATE payment_summary_tbl SET outstanding = outstanding - ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql1);
    $stmt->bind_param('is', $amount, $username);
    $stmt->execute();

    echo '<p>Payment successful.</p>';
}

// Fetch data from the table
$username = $_SESSION['username']; // Assuming you have stored the username in the session
$sql = "SELECT * FROM make_payment_tbl WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$sql1 = "SELECT * FROM payment_summary_tbl WHERE username = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param('s', $username);
$stmt1->execute();
$result1 = $stmt1->get_result();

// Initialize variables
$tot_course_fee = $amount_paid = $outstanding = $uni_fee = $paid_uni_fee = $uni_fee_outstanding = 0;

if ($result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc();
    $tot_course_fee = $row1['tot_course_fee'];
    $amount_paid = $row1['amount_paid'];
    $outstanding = $row1['outstanding'];
    $uni_fee = $row1['uni_fee'];
    $paid_uni_fee = $row1['paid_uni_fee'];
    $uni_fee_outstanding = $row1['uni_fee_outstanding'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-make_payment.css"> <!--Relevant PHP File CSS-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <div class="table">
        <table>
            <tr>
                <td>Course Fee</td>
                <td><?php echo htmlspecialchars($tot_course_fee); ?></td>
            </tr>
            <tr>
                <td>Amount Paid</td>
                <td><?php echo htmlspecialchars($amount_paid); ?></td>
            </tr>
            <tr>
                <td>Outstanding</td>
                <td><?php echo htmlspecialchars($outstanding); ?></td>
            </tr>
            <tr>
                <td>University Fee</td>
                <td><?php echo htmlspecialchars($uni_fee); ?></td>
            </tr>
            <tr>
                <td>Paid University Fee</td>
                <td><?php echo htmlspecialchars($paid_uni_fee); ?></td>
            </tr>
            <tr>
                <td>University Fee Outstanding</td>
                <td><?php echo htmlspecialchars($uni_fee_outstanding); ?></td>
            </tr>
        </table>
    </div>
    <br><br>
    <div class="table">
        <table>
            <tr>
                <th>No</th>
                <th>Next Payment Date</th>
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
    echo "<td>" . htmlspecialchars($row['nxt_pay_date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "<td>" . htmlspecialchars($amount) . "</td>";
    echo '<td>
            <form action="pay.php" method="POST">
                <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
                <input type="hidden" name="amount" value="' . htmlspecialchars($amount) . '">
                <input type="hidden" name="record_id" value="' . htmlspecialchars($record_id) . '">
                <button type="submit" class="view-link">PAY</button>
            </form></td>';
    echo "</tr>";
}
} else {
    echo "<tr><td colspan='5'>No payments found</td></tr>";
}
$conn->close();
?>
        </table>
    </div>
</div>

<div class="chart-container">
    <canvas id="myPieChart"></canvas>
</div>

<script>
    // Data for the pie chart
    const data = {
        labels: [
            'Course Fee',
            'Amount Paid',
            'Outstanding',
            'University Fee',
            'Paid University Fee',
            'University Fee Outstanding'
        ],
        datasets: [{
            label: 'Fees Breakdown',
            data: [
                <?php echo $tot_course_fee; ?>,
                <?php echo $amount_paid; ?>,
                <?php echo $outstanding; ?>,
                <?php echo $uni_fee; ?>,
                <?php echo $paid_uni_fee; ?>,
                <?php echo $uni_fee_outstanding; ?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Config for the pie chart
    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw}`;
                        }
                    }
                }
            }
        },
    };

    // Render the pie chart
    window.onload = function () {
        const ctx = document.getElementById('myPieChart').getContext('2d');
        new Chart(ctx, config);
    };
</script>
</body>
</html>
