<?php
session_start();

include_once('../connection.php');

// Loading the template.php
include_once('../assests/content/static/template.php');

// Fetch the current user's team ID
$current_username = $_SESSION['username']; // Assuming the username is stored in session
$team_id = null;
$sql = "SELECT id FROM team_members WHERE username = '" . mysqli_real_escape_string($conn, $current_username) . "'";
$result = mysqli_query($conn, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $team_id = $row['id'];
}

// Fetch all team members' data based on the team ID
$viva_data = [];
if ($team_id !== null) {
    $sql = "SELECT * FROM team_members WHERE id = " . intval($team_id);
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $viva_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viva Session Registration</title>

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="viva.css">
</head>
<body>
    <h1>Viva Session Registration</h1>
    <form id="vivaForm">
        <div id="teamMembers">
            <div class="member">
                <label>Username: <input type="text" name="username[]" required></label>
                <label>Name: <input type="text" name="name[]" required></label>
            </div>
        </div>
        <button type="button" id="addMember" class="view-link">Add Member</button>
        <button type="submit" class="view-link">Submit</button>
    </form>
    <br> 
    <br> 
    <div id="timer"></div>

    <h2>Team Members</h2>

    <div class="container">

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        
                    </tr>
                </thead>
                <tbody id="teamMembersTable">
                    <?php
                    if (count($viva_data) > 0) {
                        foreach ($viva_data as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td> <button class='tabbtn'>Manage</button>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="details">
            <?php
            if (count($viva_data) > 0) {
                $row = $viva_data[0]; // Only show details for the first record
                echo "<div class='detail-box'>";
                echo "<p><strong>Team ID:</strong> " . htmlspecialchars($row['id']) . "</p>";
                echo "<p><strong>Date:</strong> " . htmlspecialchars($row['date']) . "</p>";
                echo "<p><strong>Start:</strong> " . htmlspecialchars($row['time_slot_start']) . "</p>";
                echo "<p><strong>End:</strong> " . htmlspecialchars($row['time_slot_end']) . "</p>";
                echo "<p><strong>Classroom:</strong> " . htmlspecialchars($row['classroom']) . "</p>";
                echo "</div>";
            } else {
                echo "<p>No additional details found</p>";
            }
            ?>
        </div>
    </div>
    <script>
        const vivaData = <?php echo json_encode($viva_data); ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>
