<?php
session_start();

// Include the database connection
include_once('../../connection.php');

//Loading the template.php
include_once('../../assests/content/static/template.php');

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

    // Update the payment_summary_tbl setting the amount_paid row
    $update_sql = "UPDATE payment_summary_tbl SET amount_paid = amount_paid + ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('is', $amount, $username);
    $stmt->execute();

    // Update the payment_summary_tbl setting the outstanding row
    $update_sql1 = "UPDATE payment_summary_tbl SET outstanding = outstanding - ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql1);
    $stmt->bind_param('is', $amount, $username);
    $stmt->execute();

}

// Get the current date
$current_date = new DateTime();

// Fetch all records from the table to update penalties
$sql = "SELECT no, nxt_pay_date, penalty FROM make_payment_tbl";
$result_penalty_update = $conn->query($sql);

while ($row = $result_penalty_update->fetch_assoc()) {
    $record_id = $row['no'];
    $nxt_pay_date = new DateTime($row['nxt_pay_date']);
    $nxt_pay_date->modify('+1 month'); // Add one month to the next payment date
    $penalty = $row['penalty'];

    // Calculating the penalty
    if ($current_date > $nxt_pay_date) {
        $interval = $current_date->diff($nxt_pay_date);
        $days_overdue = $interval->days;
        $new_penalty = $days_overdue * 100;

        // Update the penalty in the make_payment_tbl table
        $update_penalty_sql = "UPDATE make_payment_tbl SET penalty = ? WHERE no = ?";
        $stmt = $conn->prepare($update_penalty_sql);
        $stmt->bind_param('ii', $new_penalty, $record_id);
        $stmt->execute();
    }
}

// Fetch data from the table for display
$username = $_SESSION['username'];
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
    <meta name="viewport" content="width=device-width, initial-scale= 1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-make_payment.css"> <!--Relevant PHP File CSS-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <div class="container">
        <div class="table-container-1">
            <div class="table">
                <table>
                    <tr>
                        <td><b>Course Fee</b></td>
                        <td><?php echo htmlspecialchars($tot_course_fee); ?></td>
                    </tr>
                    <tr>
                        <td><b>Amount Paid</b></td>
                        <td><?php echo htmlspecialchars($amount_paid); ?></td>
                    </tr>
                    <tr>
                        <td><b>Outstanding</b></td>
                        <td><?php echo htmlspecialchars($outstanding); ?></td>
                    </tr>
                    <tr>
                        <td><b>University Fee</b></td>
                        <td><?php echo htmlspecialchars($uni_fee); ?></td>
                    </tr>
                    <tr>
                        <td><b>Paid University Fee</b></td>
                        <td><?php echo htmlspecialchars($paid_uni_fee); ?></td>
                    </tr>
                    <tr>
                        <td><b>University Fee Outstanding</b></td>
                        <td><?php echo htmlspecialchars($uni_fee_outstanding); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    
        
        <div class="chart-container">
            <canvas id="myPieChart"></canvas>
        </div>   
    </div>

    <div class="container">
        <div class="table-container-2">
            <div class="table">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Next payment date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Penalty</th>
                        <th>Payment link</th>
                    </tr>
                    </thead>
                    
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $amount = $row['amount'];
                            $penalty = $row['penalty'];
                            $record_id = $row['no']; // Use record id to uniquely identify the record
                            echo "<tr>";
                            echo "<td data-cell='No:'>" . htmlspecialchars($row['no']) . "</td>";
                            echo "<td data-cell='Next Payment Date:'>" . htmlspecialchars($row['nxt_pay_date']) . "</td>";
                            echo "<td data-cell='Description:'>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td data-cell='Amount:'>" . htmlspecialchars($amount) . "</td>";
                            echo "<td data-cell='Penalty:'>" . htmlspecialchars($penalty) . "</td>";
                            echo '<td>
                                <form action="pay.php" method="POST">
                                    <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
                                    <input type="hidden" name="amount" value="' . htmlspecialchars($amount + $penalty) . '">
                                    <input type="hidden" name="record_id" value="' . htmlspecialchars($record_id) . '">
                                    <button type="submit" class="view-link">PAY</button>
                                </form></td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No payments found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
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
                    'rgba(255, 99, 132, 1)',    // Red
                    'rgba(54, 162, 235, 1)',    // Blue
                    'rgba(255, 206, 86, 1)',    // Yellow
                    'rgba(75, 192, 192, 1)',    // Teal
                    'rgba(153, 102, 255, 1)',   // Purple
                    'rgba(255, 159, 64, 1)'  
                ],
                borderWidth: 1
            }]
        };

        // Config for the doughnut chart
        const config = {
            type: 'doughnut', // Change type from 'pie' to 'doughnut'
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
            cutout: '50%' // Add this option to create a doughnut chart
        };

        // Render the pie chart
        window.onload = function () {
            const ctx = document.getElementById('myPieChart').getContext('2d');
            new Chart(ctx, config);
        };
    </script>
</body>
</html>
