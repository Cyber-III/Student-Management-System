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
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }
    $stmt->close();
    return $modules;
}

// Fetch the user's details
$user_details = get_user_details($conn, $username);
$course = $user_details['course'];
$batch_number = $user_details['batch_number'];

// Fetch modules for the user's course
$modules = get_modules($conn, $course);

// Initialize the message variable
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $module_name = sanitize_input($_POST["module_name"]);
    $module_code = sanitize_input($_POST["module_code"]);
    $date = sanitize_input($_POST["date"]);
    $description = sanitize_input($_POST["description"]);
    $status = 'pending';  // Default status

    // Insert data into the mitigations table
    $stmt = $conn->prepare("INSERT INTO mitigations (username, module_name, module_code, date, description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $module_name, $module_code, $date, $description, $status);

    if ($stmt->execute()) {
        $message = 'submitted'; // Set message to 'submitted' if successful
    } else {
        $message = 'error'; // Set message to 'error' if there's an error
    }
    $stmt->close();
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

<h1>Mitigation Form</h1>

<?php if ($message == 'submitted'): ?>
    <div class="alert alert-success">Your mitigation was submitted successfully.</div>
<?php elseif ($message == 'error'): ?>
    <div class="alert alert-danger">There was an error submitting your mitigation. Please try again.</div>
<?php endif; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="module_name">Module Name:</label><br>
    <select id="module_name" name="module_name" required onchange="updateModuleCode()">
        <option value="">Select Module Name</option>
        <?php foreach ($modules as $module): ?>
            <option value="<?php echo htmlspecialchars($module['module_name']); ?>"><?php echo htmlspecialchars($module['module_name']); ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="module_code">Module Code:</label><br>
    <select id="module_code" name="module_code" required>
        <option value="">Select Module Code</option>
        <?php foreach ($modules as $module): ?>
            <option class="module-code-option" data-module-name="<?php echo htmlspecialchars($module['module_name']); ?>" value="<?php echo htmlspecialchars($module['module_code']); ?>"><?php echo htmlspecialchars($module['module_code']); ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="date">Date:</label><br>
    <input type="date" id="date" name="date" required><br><br>

    <label for="description">Mitigation description:</label><br>
    <textarea id="description" name="description" required></textarea><br><br>

    <input type="submit" value="Submit">
</form>

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

