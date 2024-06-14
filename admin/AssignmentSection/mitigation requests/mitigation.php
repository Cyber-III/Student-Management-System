<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the logged-in user's username from the session
$username = $_SESSION['username'];

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to get the user's course and batch_number from login_tbl
function get_user_details($conn, $username) {
    $details = array();
    $stmt = $conn->prepare("SELECT course, batch_number FROM login_tbl WHERE username = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($details['course'], $details['batch_number']);
    $stmt->fetch();
    $stmt->close();
    return $details;
}

// Function to get modules from assignment_schedule table based on course
function get_modules($conn, $course) {
    $modules = [];
    $stmt = $conn->prepare("SELECT module_name, module_code, batch_number FROM assignment_schedule WHERE course = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }
    $stmt->close();
    return $modules;
}

// Function to get all mitigations from the database
function get_mitigations($conn) {
    $mitigations = [];
    $stmt = $conn->prepare("SELECT id, username, module_name, module_code, date, description, status FROM mitigations");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $mitigations[] = $row;
    }
    $stmt->close();
    return $mitigations;
}

// Function to update mitigation status
function update_mitigation_status($conn, $id, $status) {
    $stmt = $conn->prepare("UPDATE mitigations SET status = ? WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch the user's details
$user_details = get_user_details($conn, $username);
$course = $user_details['course'];
$batch_number = $user_details['batch_number'];

// Fetch modules for the user's course
$modules = get_modules($conn, $course);

// Fetch all mitigations
$mitigations = get_mitigations($conn);

// Initialize the message variable
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        // Update the status of a mitigation
        $id = sanitize_input($_POST["id"]);
        $status = sanitize_input($_POST["status"]);
        update_mitigation_status($conn, $id, $status);
        $message = 'updated';
    } else {
        // Get form data for new mitigation
        $assignment_name = sanitize_input($_POST["module_name"]);
        $module_code = sanitize_input($_POST["module_code"]);
        $date = sanitize_input($_POST["date"]);
        $description = sanitize_input($_POST["description"]);
        $status = 'pending';  // Default status

        // Insert data into the mitigations table
        $stmt = $conn->prepare("INSERT INTO mitigations (username, module_name, module_code, date, description, status) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("ssssss", $username, $assignment_name, $module_code, $date, $description, $status);

        if ($stmt->execute()) {
            $message = 'submitted'; // Set message to 'submitted' if successful
        } else {
            $message = 'error'; // Set message to 'error' if there's an error
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Mitigation</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-mitigation.css">
</head>
<body>
<div class="main_container">
    <center><h1>Mitigation Access</h1></center>
    
    <?php if ($message == 'submitted'): ?>
        <div class="alert alert-success">Your mitigation was submitted successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger">There was an error submitting your mitigation. Please try again.</div>
    <?php elseif ($message == 'updated'): ?>
        <div class="alert alert-success">The mitigation status was updated successfully.</div>
    <?php endif; ?>
    
    <div class="mitigations_container">
        <table>
            <tr>
                <th>Username</th>
                <th>Module Name</th>
                <th>Module Code</th>
                <th>Date</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php foreach ($mitigations as $mitigation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mitigation['username']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['module_name']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['module_code']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['date']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['description']); ?></td>
                    <td>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($mitigation['id']); ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" <?php echo ($mitigation['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="approved" <?php echo ($mitigation['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                <option value="cancel" <?php echo ($mitigation['status'] == 'cancel') ? 'selected' : ''; ?>>Cancel</option>
                            </select>
                            <input type="hidden" name="update_status" value="true">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
    function updateModuleCode() {
        const moduleName = document.getElementById('module_name').value;
        const moduleCodeSelect = document.getElementById('module_code');
        const moduleCodeOptions = moduleCodeSelect.querySelectorAll('.module-code-option');
        
        // Reset module code select
        moduleCodeSelect.value = '';
        
        // Hide all options first
        moduleCodeOptions.forEach(option => {
            option.style.display = 'none';
        });
        
        // Show only the options that match the selected module name
        moduleCodeOptions.forEach(option => {
            if (option.getAttribute('data-module-name') === moduleName) {
                option.style.display = 'block';
            }
        });
    }
</script>

</body>
</html>
