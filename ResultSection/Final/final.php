<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

$username = $_SESSION['username']; // Assuming you have stored the username in the session

$sql = "SELECT * FROM final_result WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    die("Error in SQL query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-final.css">

    <title>Final Results</title>
</head>
<body>
    
<div class="table">
    <table>
        <tr>
            <th>Module Code</th>
            <th>Module Name</th>
            <th>Assignment Result</th>
            <th>Presentation Result</th>
            <th>Exam Result</th>
            <th>Final Result</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['module_code']) . "</td>";
                echo "<td>" . htmlspecialchars($row['module_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['coursework_result']) . "</td>";
                echo "<td>" . htmlspecialchars($row['presentation_result']) . "</td>";
                echo "<td>" . htmlspecialchars($row['exam_result']) . "</td>";
                echo "<td>" . htmlspecialchars($row['final_result']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No submissions found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

</body>
</html>
