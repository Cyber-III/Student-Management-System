<?php
// Start the session
session_start();

// Include the database connection
include_once('connection.php');

// Loading the HTML template
require './assests/content/static/template.php';

// Assuming validate function is defined elsewhere
function validate($tableName) {
    // Implement your validation or sanitization logic here
    // For simplicity, let's assume it just returns the provided table name
    return $tableName;
}

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the latest notices from the database
$sql = "SELECT subject FROM notice ORDER BY added_date DESC LIMIT 5";
$result = $conn->query($sql);

// Create an array to hold the notices
$notices = [];

if ($result->num_rows > 0) {
    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}

function getCount($tableName) {
    global $conn;

    $table = validate($tableName); // Validate the table name (implement this function)
    $query = "SELECT COUNT(*) as count FROM $table"; // Count the rows
    $result = $conn->query($query); // Execute the query

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalCount = $row['count']; // Get the count value
        return $totalCount;
    } else {
        return 0; // Return 0 if query fails or no rows found
    }
}

// Example usage to get count of rows in login_tbl (assuming username is the primary key)
$count = getCount('login_tbl');
$count1 = getCount('batches');
$count2 = getCount('lecturers');

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="style-welcome.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pics/favicon-32x32.png">

    <style>
        .chart-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 20px;
        }

        .chart {
            flex: 0;
            margin: 0 200px;
        }
    </style>
</head>
<body>
    
<div class="container1">
    <div class="counter-wrapper">
        <div class="counter-box colored">
            <i class="fa fa-thumbs-o-up"></i>
            <span class="counter"><?= $count ?></span>
            <p>Student Count</p>
        </div>

        <div class="counter-box colored">
            <i class="fa fa-thumbs-o-up"></i>
            <span class="counter"><?= $count1 ?></span>
            <p>Batches Count</p>
        </div>

        <div class="counter-box colored">
            <i class="fa fa-thumbs-o-up"></i>
            <span class="counter"><?= $count2 ?></span>
            <p>Lecturers Count</p>
        </div>

        <div class="counter-box colored">
            <i class="fa fa-thumbs-o-up"></i>
            <span class="counter">3545</span>
            <p>Today Attendance</p>
        </div>
    </div>
</div>

<div class="container d-flex justify-container-center">
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart" style="margin-left:300px;">
                    <canvas id="summaryChart" width="350" height="350"></canvas>
                </div>
                <div class="chart" style="margin-left:50px;">
                    <canvas id="summaryChart1" width="350" height="350"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.counter').each(function() {
        var $this = $(this);
        var countTo = parseInt($this.text(), 10);
        
        $({ countNum: 0 }).animate({ countNum: countTo }, {
            duration: 4000,
            easing: 'swing',
            step: function() {
                $this.text(Math.ceil(this.countNum));
            },
            complete: function() {
                $this.text(countTo);
            }
        });
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('summaryChart').getContext('2d');
        var summaryChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Lecturers', 'Students', 'Batches'],
                datasets: [{
                    label: 'Summary',
                    backgroundColor: ['#007BFF', '#28A745', '#FFC107'],
                    data: [<?= $count2 ?>, <?= $count ?>, <?= $count1 ?>]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    });
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('summaryChart1').getContext('2d');
        var summaryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lecturers', 'Students', 'Batches'],
                datasets: [{
                    label: 'Count',
                    backgroundColor: '#007BFF',
                    borderColor: '#007BFF',
                    data: [<?= $count2 ?>, <?= $count ?>, <?= $count1 ?>],
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(0);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Category'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
