<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Fetch payment data from the database for the current year (2024)
$query = "
    SELECT nxt_pay_date, amount, penalty 
    FROM make_payment_tbl 
    WHERE YEAR(nxt_pay_date) = 2024
";
$result = $conn->query($query);

// Check for query execution errors
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

$payments = [];
$penalties = [];

// Process query results
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
    if ($row['penalty'] > 0) {
        $date = new DateTime($row['nxt_pay_date']);
        $year = $date->format('Y');
        $month = $date->format('m');
        if (!isset($penalties[$year])) {
            $penalties[$year] = array_fill(0, 12, 0);
        }
        $penalties[$year][(int)$month - 1] += (int)$row['penalty'];
    }
}

// Close the database connection
$conn->close();

// Prepare data for the payments chart
$monthlyPayments = array_fill(0, 12, 0);
foreach ($payments as $payment) {
    $date = new DateTime($payment['nxt_pay_date']);
    $monthIndex = (int)$date->format('m') - 1;
    $monthlyPayments[$monthIndex] += (int)$payment['amount'];
}

// Labels for each month
$labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Convert data to JSON format for JavaScript usage
$monthlyPaymentsJson = json_encode($monthlyPayments);
$penaltiesJson = json_encode($penalties);
$labelsJson = json_encode($labels);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Payments and Penalties</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="view_payments.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Upcoming Payments</h2>
        <canvas id="paymentsChart" width="400" height="200"></canvas>
    </div>

    <br> <br>

    <div class="container">
        <h2>Penalty Details</h2>
        <canvas id="penaltiesChartCurrentYear" width="400" height="200"></canvas>
    </div>

    <br> <br>

    <script>
        // Get the data from PHP
        const monthlyPayments = <?php echo $monthlyPaymentsJson; ?>;
        const penalties = <?php echo $penaltiesJson; ?>;
        const labels = <?php echo $labelsJson; ?>;
        
        // Create the payments bar chart with animation
        const ctxPayments = document.getElementById('paymentsChart').getContext('2d');
        const paymentsChart = new Chart(ctxPayments, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Upcoming Payments',
                    data: monthlyPayments,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                animation: {
                    duration: 2000, // Animation duration in milliseconds
                    easing: 'easeInOutQuart' // Easing function for smooth animation
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Prepare data for the penalties charts
        const currentYear = 2024;
        const penaltiesCurrentYear = penalties[currentYear] || Array(12).fill(0);
        
        // Labels for the penalties charts (months)
        const monthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        // Create the penalties bar chart for the current year
        const ctxPenaltiesCurrentYear = document.getElementById('penaltiesChartCurrentYear').getContext('2d');
        const penaltiesChartCurrentYear = new Chart(ctxPenaltiesCurrentYear, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Penalties',
                    data: penaltiesCurrentYear,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
