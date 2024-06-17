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

    <h2>Team Members</h2>

    <div class="container">

    <div class="table">
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="teamMembersTable">
            <?php
            if (count($viva_data) > 0) {
                foreach ($viva_data as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td> <button class='tabbtn btn btn-primary' data-toggle='modal' data-target='#manageModal' data-username='" . htmlspecialchars($row['username']) . "' data-name='" . htmlspecialchars($row['name']) . "'>Manage</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No records found</td></tr>";
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

    <div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="manageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageModalLabel">Manage User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="manageForm">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <button type="button" class="btn btn-primary" id="editButton">Edit</button>
                    <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    const vivaData = <?php echo json_encode($viva_data); ?>;
</script>
<script src="script.js"></script>
<script>
    $('#manageModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var username = button.data('username');
        var name = button.data('name');
        
        var modal = $(this);
        modal.find('#username').val(username);
        modal.find('#name').val(name);
    });

    $('#editButton').on('click', function () {
        var username = $('#username').val();
        var name = $('#name').val();

        console.log("Editing user:", username, name); // Debugging

        $.ajax({
            url: 'update.php',
            type: 'POST',
            data: { username: username, name: name },
            success: function(response) {
                console.log("Update response:", response); // Debugging
                window.location.href = 'viva.php?message=updated';
            },
            error: function(error) {
                console.log("Update error:", error); // Debugging
            }
        });
    });

    $('#deleteButton').on('click', function () {
        var username = $('#username').val();

        // Display confirmation dialog
        var confirmation = confirm("Are you sure?");

        if (confirmation) {
            // If the user confirms, proceed with the AJAX request to delete the record
            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: { username: username },
                success: function(response) {
                    console.log("Delete response:", response); // Debugging
                    window.location.href = 'viva.php?message=deleted'; // Redirect to show the delete message
                },
                error: function(error) {
                    console.log("Delete error:", error); // Debugging
                    alert('Error deleting record');
                }
            });
        } else {
            // If the user cancels, do nothing
            console.log("Delete canceled");
        }
    });

</script>

</body>
</html>
