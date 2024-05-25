<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch the data from the database
$sql = "SELECT * FROM course_modules";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Create an array to hold the data
    $modules = [];

    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Module</title>
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-courseModule.css">
</head>
<body>
<div class="container">
    <div class="table">
        <table>
            <tr>
                <th>Semester</th>
                <th>Module Name</th>
                <th>Module Code</th>
                <th>Date</th>
                <th>Duration</th>
                <th>#Assign</th>
                <th></th>
            </tr>
            <?php if (!empty($modules)): ?>
                <?php foreach ($modules as $module): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($module['semester']); ?></td>
                        <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                        <td><?php echo htmlspecialchars($module['module_code']); ?></td>
                        <td><?php echo htmlspecialchars($module['date']); ?></td>
                        <td><?php echo htmlspecialchars($module['duration']); ?></td>
                        <td><?php echo htmlspecialchars($module['assignments_completed']); ?></td>
                        <td><a class="view-link" href="<?php echo htmlspecialchars($module['view_link']); ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No data found</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
</body>
</html>
