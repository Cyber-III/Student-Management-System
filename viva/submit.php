<?php
include_once('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch viva name from the form submission
    $viva_name = $_POST['viva_name'];
    $usernames = $_POST['username'];
    $names = $_POST['name'];

    // Fetch the schedule details from viva_schedules table based on the viva name
    $schedule_sql = "SELECT batch_number, date, location, viva_name FROM viva_schedules WHERE viva_name = ? ORDER BY date DESC LIMIT 1";
    $stmt = $conn->prepare($schedule_sql);
    if ($stmt === false) {
        die('SQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param('s', $viva_name);
    $stmt->execute();
    $schedule_result = $stmt->get_result();

    if ($schedule_result === false) {
        die('SQL error: ' . $conn->error);
    }

    if ($schedule_result->num_rows > 0) {
        $schedule_row = $schedule_result->fetch_assoc();
        $batch_number = $schedule_row['batch_number'];
        $date = $schedule_row['date'];
        $classroom = $schedule_row['location'];
        $module_name = $schedule_row['viva_name'];
    } else {
        die('No schedule found in the viva_schedules table for the provided viva name.');
    }

    // Fetch the last assigned time slot
    $sql = "SELECT MAX(time_slot_end) as last_slot FROM team_members WHERE date = ?";
    $stmt_last_slot = $conn->prepare($sql);
    if ($stmt_last_slot === false) {
        die('SQL prepare error: ' . $conn->error);
    }
    $stmt_last_slot->bind_param('s', $date);
    $stmt_last_slot->execute();
    $result_last_slot = $stmt_last_slot->get_result();

    if ($result_last_slot === false) {
        die('SQL error: ' . $conn->error);
    }

    $last_slot = "09:00:00"; // Default start time

    if ($result_last_slot->num_rows > 0) {
        $row = $result_last_slot->fetch_assoc();
        if (!empty($row['last_slot'])) {
            $last_slot = $row['last_slot'];
        }
    }

    // Initialize start time
    $start_time = new DateTime($last_slot);

    // Check if the initial end time is beyond 16:00:00 and reset if needed
    if ($start_time >= new DateTime('16:00:00')) {
        $start_time = new DateTime('09:00:00');
    }

    // Calculate the end time for the current batch
    $end_time = clone $start_time;
    $end_time->add(new DateInterval('PT15M'));

    // Skip interval between 12:00 and 13:00
    if ($start_time >= new DateTime('12:00:00') && $start_time < new DateTime('13:00:00')) {
        $start_time = new DateTime('13:00:00');
        $end_time = clone $start_time;
        $end_time->add(new DateInterval('PT15M'));
    }

    // Check if the end time is beyond 16:00:00 and reset if needed
    if ($end_time > new DateTime('16:00:00')) {
        $start_time = new DateTime('09:00:00');
        $time_diff = $end_time->diff(new DateTime('16:00:00'));
        $end_time = clone $start_time;
        $end_time->add($time_diff);
    }

    $time_slot_start = $start_time->format('H:i:s');
    $time_slot_end = $end_time->format('H:i:s');

    // Generate a unique ID for this batch based on the current timestamp
    $batch_id = time(); // Using timestamp to ensure uniqueness for the batch

    // Prepare the SQL statement with placeholders for insertion
    $stmt_insert = $conn->prepare("INSERT INTO team_members (id, username, name, time_slot_start, time_slot_end, batch_number, date, classroom, viva_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt_insert === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Loop through each username and insert into database
    foreach ($usernames as $index => $username) {
        // Bind parameters
        $stmt_insert->bind_param('issssssss', $batch_id, $username, $names[$index], $time_slot_start, $time_slot_end, $batch_number, $date, $classroom, $module_name);

        // Execute the statement
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            echo "Username $username registered for the Viva session.<br>";
        } else {
            echo "Failed to register username $username for the Viva session.<br>";
        }
    }

    // Close the statement
    $stmt_insert->close();

    echo "Team members successfully registered for the Viva session!";

} else {
    echo "Invalid request method.";
}

$conn->close();
?>
