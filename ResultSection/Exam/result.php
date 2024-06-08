<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

$username = $_SESSION['username']; // Assuming you have stored the username in the session
$sql = "SELECT exam_name, submission_date, results, file_path FROM exam_submission WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-result.css">

    <title>Document</title>
</head>
<div class="container">
    <div class="table-container-2">
        <div class="table">
                <table>
                    <tr>
                        <th>Exam Name</th>
                        <th>Submission Date</th>
                        <th>Result</th>
                        <th>View Submission</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-label='Exam Name'>" . htmlspecialchars($row['exam_name']) . "</td>";
                            echo "<td data-label='Sub Date'>" . htmlspecialchars($row['submission_date']) . "</td>";
                            echo "<td data-label='Result'>" . htmlspecialchars($row['results']) . "</td>";
                            echo '<td data-label="View Submission">';
                            echo '<a href="' . htmlspecialchars($row['file_path']) . '" target="_blank"><button type="submit" class="view-link">View</button></a>';
                            echo '</td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No exam submissions found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
        </div>
    </div>
</div>
</body>
</html>
