<?php
include_once('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $viva_name = htmlspecialchars($_POST['viva_name']);
    $usernames = array_map('htmlspecialchars', $_POST['username']);
    $names = array_map('htmlspecialchars', $_POST['name']);

    // Fetch the latest viva schedule
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
        die('No schedule found for the provided viva name.');
    }

    // Fetch the last time slot for the given date
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

    $last_slot = "09:00:00"; // Default start time if no slots exist
    if ($result_last_slot->num_rows > 0) {
        $row = $result_last_slot->fetch_assoc();
        if (!empty($row['last_slot'])) {
            $last_slot = $row['last_slot'];
        }
    }

    // Initialize start time and date objects
    $start_time = new DateTime($last_slot);
    $current_date = new DateTime($date);

    // Function to calculate the next time slot
    function calculateNextTimeSlot($start_time, $current_date) {
        // Define the workday intervals
        $morning_start = new DateTime('09:00:00');
        $morning_end = new DateTime('12:00:00');
        $lunch_start = new DateTime('12:00:00');
        $lunch_end = new DateTime('13:00:00');
        $afternoon_start = new DateTime('13:00:00');
        $day_end = new DateTime('16:00:00');

        // Check if the current start time is within the lunch break
        if ($start_time >= $lunch_start && $start_time < $lunch_end) {
            $start_time = $lunch_end;
        }

        // Calculate end time by adding 15 minutes to the start time
        $end_time = clone $start_time;
        $end_time->add(new DateInterval('PT15M'));

        // If the end time crosses the morning end time but is before lunch start
        if ($end_time > $morning_end && $end_time <= $lunch_start) {
            $start_time = $afternoon_start;
            $end_time = clone $start_time;
            $end_time->add(new DateInterval('PT15M'));
        }

        // If the end time crosses the day end time
        if ($end_time > $day_end) {
            // Move to the next day and start from 9 am
            $current_date->modify('+1 day');
            $start_time = $morning_start;
            $end_time = clone $start_time;
            $end_time->add(new DateInterval('PT15M'));
        }

        return [$start_time, $end_time, $current_date];
    }

    // Prepare the SQL statement with placeholders for insertion
    $stmt_insert = $conn->prepare("INSERT INTO team_members (id, username, name, time_slot_start, time_slot_end, batch_number, date, classroom, viva_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt_insert === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Loop through each username and insert into the database
    foreach ($usernames as $index => $username) {
        // Calculate time slots
        list($start_time, $end_time, $current_date) = calculateNextTimeSlot($start_time, $current_date);

        $time_slot_start = $start_time->format('H:i:s');
        $time_slot_end = $end_time->format('H:i:s');
        $formatted_date = $current_date->format('Y-m-d');

        // Generate a unique ID for this batch based on the current timestamp
        $batch_id = time() + $index; // Using timestamp to ensure uniqueness for the batch

        // Bind parameters
        $stmt_insert->bind_param('issssssss', $batch_id, $username, $names[$index], $time_slot_start, $time_slot_end, $batch_number, $formatted_date, $classroom, $module_name);

        // Execute the statement
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            echo "Username $username registered for the Viva session.<br>";
        } else {
            echo "Failed to register username $username for the Viva session.<br>";
        }

        // Update start_time for the next slot
        $start_time = $end_time;
    }

    // Close the statement
    $stmt_insert->close();

    echo "Team members successfully registered for the Viva session!";
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
