<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Fetch the data from the database
$sql = "SELECT * FROM notice";
$result = $conn->query($sql);

// Create an array to hold the data
$notices = [];

if ($result->num_rows > 0) {
    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}

// Close the database connection
$conn->close();
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
