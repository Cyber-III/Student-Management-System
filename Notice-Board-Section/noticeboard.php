<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Assume $username is retrieved from the session or some login mechanism
$username = $_SESSION['username'];

$sql = "SELECT course, batch_number FROM login_tbl WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $course = $user['course'];
        $batch_number = $user['batch_number'];

        // Store batch number in session
        $_SESSION['batch_number'] = $batch_number;

        // Use backticks for the table name to avoid SQL syntax errors
        $sql = "SELECT * FROM `batch-notice` WHERE batch_number = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $batch_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $notices = $result->fetch_all(MYSQLI_ASSOC); // Fetch all records
            $stmt->close();
        } else {
            die("Error in SQL query: " . $conn->error);
        }
    } else {
        die("User not found.");
    }
} else {
    die("Error in SQL query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Board</title>
    <link rel="stylesheet" href="../../style-template.css"> <!-- Template File CSS -->
    <link rel="stylesheet" href="style-noticeBoard.css">
</head>
<body>
<div class="container">
    <div class="table">
        <table>
            <tr>
                <th>Subject</th>
                <th>Added Date</th>
                <th>View</th>
            </tr>
            <?php if (!empty($notices)): ?>
                <?php foreach ($notices as $notice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($notice['subject']); ?></td>
                        <td><?php echo htmlspecialchars($notice['added_date']); ?></td>
                        <td><button class="view-link" onclick="openModal('<?php echo htmlspecialchars($notice['subject']); ?>', '<?php echo htmlspecialchars($notice['added_date']); ?>', '<?php echo htmlspecialchars($notice['view_link']); ?>')">View</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No data found</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalSubject"></h2>
        <p id="modalAddedDate"></p>
        <p id="modalViewLink"></p>
    </div>
</div>

<script>
    // Function to open the modal with notice details
    function openModal(subject, addedDate, viewLink) {
        var modal = document.getElementById('myModal');
        var modalSubject = document.getElementById('modalSubject');
        var modalAddedDate = document.getElementById('modalAddedDate');
        var modalViewLink = document.getElementById('modalViewLink');

        modalSubject.textContent = subject;
        modalAddedDate.textContent = 'Added Date: ' + addedDate;
        modalViewLink.innerHTML = 'View Link: <a href="' + viewLink + '" target="_blank">' + viewLink + '</a>';

        modal.style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = 'none';
    }

    // Close the modal if user clicks outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
</body>
</html>
