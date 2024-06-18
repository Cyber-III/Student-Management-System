<?php
session_start();

include_once('../connection.php');

// Loading the template.php
include_once('../assests/content/static/template.php');

// Fetch the current user's team ID
$current_username = $_SESSION['username']; // Assuming the username is stored in session
$team_id = null;
$sql = "SELECT viva_name, id FROM team_members WHERE username = '" . mysqli_real_escape_string($conn, $current_username) . "'";
$result = mysqli_query($conn, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $team_id = $row['id'];
    $exam_name = $row['viva_name'];
}

// Fetch the submitted details from the form
$module_name = isset($_POST['module_name']) ? $_POST['module_name'] : '';
$exam_name = isset($_POST['exam_name']) ? $_POST['exam_name'] : '';

// Fetch all team members' data based on the team ID and the selected viva name
$viva_data = [];
if ($team_id !== null && !empty($exam_name)) {
    $sql = "SELECT * FROM team_members WHERE id = " . intval($team_id) . " AND viva_name = '" . mysqli_real_escape_string($conn, $exam_name) . "'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $viva_data[] = $row;
    }
}


$message = isset($_GET['message']) ? $_GET['message'] : '';

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

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<marquee><p class="text">*If you register for a team work, please fill the form only the group leader</p></marquee>


    <h1>Viva Session Registration</h1>
    <?php if ($message == 'updated'): ?>
        <div class="alert alert-success">Records are updated successfully.</div>
    <?php elseif ($message == 'deleted'): ?>
        <div class="alert alert-danger">Records are deleted successfully.</div>
    <?php endif; ?>
    <form id="vivaForm" action="submit.php" method="POST">
        <label for="viva_name">Viva Name:</label>
        <input type="text" id="viva_name" name="viva_name" value="<?= htmlspecialchars($exam_name) ?>" readonly>

        <label for="module_name">Module Name:</label>
        <input type="text" id="module_name" name="module_name" value="<?= htmlspecialchars($module_name) ?>" readonly>

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

    

<script>
    const vivaData = <?php echo json_encode($viva_data); ?>;
</script>
<script src="script.js"></script>

</body>
</html>
