<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $module_code = mysqli_real_escape_string($conn, $_POST['module_code']);
    $batch_number = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $viva_name = mysqli_real_escape_string($conn, $_POST['viva_name']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Insert the data into the database
    $sql = "INSERT INTO viva_schedules (course, module_name, module_code, batch_number, viva_name, date, location) VALUES 
    ('$course', '$module_name', '$module_code', '$batch_number', '$viva_name', '$date', '$location')";

    if (mysqli_query($conn, $sql)) {
        header("Location: viva.php?message=insert");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the form with a success message
    header("Location: viva_form.php?message=" . urlencode($message));
    exit();
}

// Fetch distinct viva names for the search dropdown
$viva_names = [];
$result = mysqli_query($conn, "SELECT DISTINCT viva_name FROM team_members");
while ($row = mysqli_fetch_assoc($result)) {
    $viva_names[] = $row['viva_name'];
}

// Fetch distinct batch numbers for the search dropdown
$batch_numbers = [];
$batch_query = "SELECT DISTINCT batch_no FROM batches";
$result = mysqli_query($conn, $batch_query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $batch_numbers[] = $row['batch_no'];
    }
} else {
    die("Error fetching batch numbers: " . mysqli_error($conn));
}

// Initialize exam_schedule_data
$exam_schedule_data = [];

// Fetch all data from the team_members table, filtered by viva name and/or batch number and/or ID if search input is provided
$search_viva_name = isset($_GET['search_viva_name']) ? htmlspecialchars($_GET['search_viva_name']) : '';
$search_batch_number = isset($_GET['search_batch_number']) ? htmlspecialchars($_GET['search_batch_number']) : '';
$search_id = isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : '';

if (!empty($search_viva_name) || !empty($search_batch_number) || !empty($search_id)) {
    $query = "SELECT * FROM team_members WHERE 1=1";
    if (!empty($search_viva_name)) {
        $query .= " AND viva_name = '" . mysqli_real_escape_string($conn, $search_viva_name) . "'";
    }
    if (!empty($search_batch_number)) {
        $query .= " AND batch_number = '" . mysqli_real_escape_string($conn, $search_batch_number) . "'";
    }
    if (!empty($search_id)) {
        $query .= " AND id = '" . mysqli_real_escape_string($conn, $search_id) . "'";
    }
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $exam_schedule_data[] = $row;
        }
    } else {
        die("Error fetching exam schedule data: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Details</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="viva.css">
    
    <style>
        #print-viva-name {
            display: none; /* Hide by default */
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .print-table, .print-table * {
                visibility: visible;
            }
            .print-table {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print-table th:nth-child(9), .print-table td:nth-child(9) {
                display: none;
            }
            #print-viva-name {
                display: block; /* Show in print view */
                margin-bottom: 20px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Display only in print view -->
    <div id="print-viva-name"><?= htmlspecialchars($search_viva_name) ?></div>

    <!-- Print button -->
    <button onclick="printTable()" class="view-link1" style="float: right; margin-top: 120px; margin-right: 150px;">Print Table</button>

    <!-- Search bars -->
    <div class="search-bar">
        <form method="GET" action="">
            <label for="search_viva_name">Search by Viva Name:</label>
            <select id="search_viva_name" name="search_viva_name">
                <option value="">Select Viva Name</option>
                <?php foreach ($viva_names as $viva_name): ?>
                    <option value="<?= htmlspecialchars($viva_name) ?>" <?= $viva_name === $search_viva_name ? 'selected' : '' ?>><?= htmlspecialchars($viva_name) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" id="search-icon"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <label for="search_batch_number">Search by Batch Number:</label>
            <select id="search_batch_number" name="search_batch_number">
                <option value="">Select Batch Number</option>
                <?php foreach ($batch_numbers as $batch_number): ?>
                    <option value="<?= htmlspecialchars($batch_number) ?>" <?= $batch_number === $search_batch_number ? 'selected' : '' ?>><?= htmlspecialchars($batch_number) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" id="search-icon"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <label for="search_id">Search by ID:</label>
            <input type="text" id="search_id" name="search_id" value="<?= htmlspecialchars($search_id) ?>" placeholder="Enter ID">
            <button type="submit" id="search-icon"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <br>

    <!-- Table section -->
    <div class="table">
        <table class="print-table">
            <thead>
                <tr>
                    <th>Viva Name</th>
                    <th>Team ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Classroom</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="exam-schedule-tbody">
                <?php if (!empty($exam_schedule_data)): ?>
                    <?php foreach ($exam_schedule_data as $row): ?>
                        <tr>
                            <td data-cell="Viva Name"><?= htmlspecialchars($row['viva_name']) ?></td>
                            <td data-cell="Team ID"><?= htmlspecialchars($row['id']) ?></td>
                            <td data-cell="Username"><?= htmlspecialchars($row['username']) ?></td>
                            <td data-cell="Name"><?= htmlspecialchars($row['name']) ?></td>
                            <td data-cell="Date"><?= htmlspecialchars($row['date']) ?></td>
                            <td data-cell="Start"><?= htmlspecialchars($row['time_slot_start']) ?></td>
                            <td data-cell="End"><?= htmlspecialchars($row['time_slot_end']) ?></td>
                            <td data-cell="Classroom"><?= htmlspecialchars($row['classroom']) ?></td>
                            <td data-cell="Action"><button onclick="manageExam(this.parentNode.parentNode)" class="manage-button view-link">Manage</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No team details.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for printing the table -->
    <script>
        function printTable() {
            window.print();
        }
    </script>
</body>
</html>
