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

// Retrieve mitigations data for the logged-in user
$stmt = $conn->prepare("SELECT module_name, module_code, date, description, status FROM mitigations WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store the retrieved mitigations
$mitigations = [];
while ($row = $result->fetch_assoc()) {
    $mitigations[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Mitigations</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-view_mitigation.css">
</head>
<body>

<h1>View Mitigations</h1>

<?php if (!empty($mitigations)): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Module Name</th>
                <th>Module Code</th>
                <th>Date</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mitigations as $mitigation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mitigation['module_name']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['module_code']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['date']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['description']); ?></td>
                    <td><?php echo htmlspecialchars($mitigation['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No mitigations found for this user.</div>
<?php endif; ?>

</body>
</html>
