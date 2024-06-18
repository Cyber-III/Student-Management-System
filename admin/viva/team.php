<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$search_viva_name = isset($_GET['search_viva_name']) ? htmlspecialchars($_GET['search_viva_name']) : '';
$search_batch_number = isset($_GET['search_batch_number']) ? htmlspecialchars($_GET['search_batch_number']) : '';
$search_id = isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : '';

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
    <title>Document</title>

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="viva.css">
</head>
<body>
    <!-- Search bar for Viva Name -->
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

    <!-- Search bar for Batch Number -->
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

    <!-- Search bar for ID -->
    <div class="search-bar">
        <form method="GET" action="">
            <label for="search_id">Search by ID:</label>
            <input type="text" id="search_id" name="search_id" value="<?= htmlspecialchars($search_id) ?>" placeholder="Enter ID">
            <button type="submit" id="search-icon"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <br>


    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Viva</th>
                    <th>Team ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Class</th>
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
                    <!-- Show a message or keep the table body empty -->
                    <tr>
                        <td colspan="9">No team details.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function manageExam(row) {
            
        }
    </script>
</body>
</html>
