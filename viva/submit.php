<?php
include_once('../connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernames = $_POST['username'];
    $names = $_POST['name'];

    // Fetch the last assigned time slot
    $sql = "SELECT MAX(time_slot_end) as last_slot FROM team_members";
    $result = $conn->query($sql);
    $last_slot = "09:00:00"; // Default start time

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
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

    // Loop through each username and insert into database
    $anyInserted = false;
    foreach ($usernames as $index => $username) {
        // Generate a unique ID for this batch based on the current timestamp
        $batch_id = time(); // Using timestamp to ensure uniqueness for the batch

        // Loop through each username and insert into database
        $anyInserted = false;
        foreach ($usernames as $index => $username) {
            // Prepare the SQL statement with placeholders for insertion
            $stmt = $conn->prepare("INSERT INTO team_members (id, username, name, time_slot_start, time_slot_end) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die('MySQL prepare error: ' . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param('issss', $batch_id, $username, $names[$index], $time_slot_start, $time_slot_end);

            // Check if the username already exists
            $escaped_username = $conn->real_escape_string($username);
            $check_sql = "SELECT COUNT(*) as count FROM team_members WHERE username = '$escaped_username'";
            $check_result = $conn->query($check_sql);
            $row = $check_result->fetch_assoc();

            if ($row['count'] == 0) {
                $stmt->execute();
                $anyInserted = true;
                echo "Username $username registered for the Viva session.<br>";
            } else {
                echo "Username $username already exists in the database.<br>";
            }

            $stmt->close();
        }

    }

    if ($anyInserted) {
        echo "Team members successfully registered for the Viva session!";
    }

    // Move start time to the next slot for future batches
    $start_time = clone $end_time;

} else {
    echo "Invalid request method.";
}

$conn->close();
?>
