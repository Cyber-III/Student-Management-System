<?php
// Start the session
session_start();

// Start output buffering
ob_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Check if the form is submitted for updating or deleting the assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $assignment_name = $_POST['assignment_name'];
    $action = $_POST['action'];

    if ($action === 'edit') {
        $results = $_POST['results'];
        $feedback = $_POST['feedback'];

        $update_sql = "UPDATE assignments SET results = ?, feedback = ? WHERE username = ? AND assignment_name = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('ssss', $results, $feedback, $username, $assignment_name);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = "Assignment updated successfully.";
    } elseif ($action === 'delete') {
        $delete_sql = "DELETE FROM assignments WHERE username = ? AND assignment_name = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param('ss', $username, $assignment_name);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = "Assignment deleted successfully.";
    }

    // Flush the output buffer and redirect
    ob_end_clean();
    header("Location: assignment_submission.php");
    exit();
}

// Updated SQL query to select all data from the assignments table
$sql = "SELECT batch_number, username, assignment_name, results, feedback, file_path FROM assignments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-assignment_submission.css">

    <title>Assignment Submissions</title>

    <style>
        
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function manageAssignment(row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('modal').style.display = 'block';
                document.getElementById('manage-username').value = cells[0].textContent;
                document.getElementById('manage-assignment_name').value = cells[2].textContent;
                document.getElementById('manage-results').value = cells[4].textContent;
                document.getElementById('manage-feedback').value = cells[5].textContent;
            }

            document.querySelectorAll('.manage-button').forEach(button => {
                button.addEventListener('click', function() {
                    manageAssignment(this.closest('tr'));
                });
            });

            function closeModal() {
                document.getElementById('modal').style.display = 'none';
            }

            document.querySelector('.close').addEventListener('click', closeModal);

            // Close the modal when clicking outside of the modal content
            window.onclick = function(event) {
                if (event.target === document.getElementById('modal')) {
                    closeModal();
                }
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="topic">
            <br><br><br>
            <h1>Assignments</h1>
        </div>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <div class="table-container-2">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Batch</th>
                            <th>Exam Name</th>
                            <th>Result</th>
                            <th>Feedback</th>
                            <th>Download Submission</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                    $file_path = str_replace("uploads", "../../../ResultSection/Assignment/uploads", $row['file_path']);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['batch_number']) ?></td>
                                    <td><?= htmlspecialchars($row['assignment_name']) ?></td>
                                    <td><?= htmlspecialchars($row['results']) ?></td>
                                    <td><?= htmlspecialchars($row['feedback']) ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($file_path) ?>" target="_blank">
                                            <button type="button" class="view-link">Download</button>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="manageAssignment(this.parentNode.parentNode)" class="manage-button view-link">Manage</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8">No assignment submissions found</td></tr>
                        <?php endif; ?>
                        <?php $conn->close(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            
            <h2>Edit Assignment Submission</h2>
            
            <form action="assignment_submission.php" method="POST">
                <input type="hidden" name="username" id="manage-username">
                <input type="hidden" name="assignment_name" id="manage-assignment_name">
                
                <div class="form-group">
                    <label for="manage-results">Result:</label>
                    <input type="text" id="manage-results" name="results">
                </div>

                <div class="form-group">
                    <label for="manage-feedback">Feedback:</label>
                    <input type="text" id="manage-feedback" name="feedback">
                </div>

                <div class="form-group">
                    <div class="button-container">
                        <button type="submit" name="action" value="edit" class="view-link">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
