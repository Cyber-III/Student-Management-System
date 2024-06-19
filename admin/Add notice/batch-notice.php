<?php
// Start output buffering
ob_start();

// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Function to sanitize input
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Fetch common notices
$com_notice = [];
$result1 = mysqli_query($conn, "SELECT * FROM notice");
while ($row = mysqli_fetch_assoc($result1)) {
    $com_notice[] = $row;
}

// Fetch awarding batch numbers
$batch_numbers = [];
$result2 = mysqli_query($conn, "SELECT DISTINCT batch_no FROM batches");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['batch_no'];
}

$message = isset($_GET['message']) ? $_GET['message'] : '';

// Fetch batch notices
$batch_notice = [];
$result3 = mysqli_query($conn, "SELECT * FROM `batch-notice`");
while ($row = mysqli_fetch_assoc($result3)) {
    $batch_notice[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['common_notice_form'])) {
        // Handle common notice form submission
        $subject = sanitize_input($conn, $_POST['subject']);
        $added_date = sanitize_input($conn, $_POST['added_date']);
        $view_link = sanitize_input($conn, $_POST['view_link']);
        
        $query = "INSERT INTO notice (subject, added_date, view_link) VALUES ('$subject', '$added_date', '$view_link')";
        
        if (mysqli_query($conn, $query)) {
            echo "Common notice added successfully.";
        } else {
            if (mysqli_errno($conn) == 1062) {
                echo "You have already inserted this notice.";
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['batch_notice_form'])) {
        // Handle batch notice form submission
        $subject = sanitize_input($conn, $_POST['subject']);
        $added_date = sanitize_input($conn, $_POST['added_date']);
        $view_link = sanitize_input($conn, $_POST['view_link']);
        $batches = $_POST['batch'];
        
        foreach ($batches as $batch) {
            $batch = sanitize_input($conn, $batch);
            $query = "INSERT INTO `batch-notice` (batch_number, subject, added_date, view_link) VALUES ('$batch', '$subject', '$added_date', '$view_link')";
            
            if (mysqli_query($conn, $query)) {
                echo "Batch notice added successfully.";
            } else {
                if (mysqli_errno($conn) == 1062) {
                    echo "You have already inserted this notice for batch $batch.";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
        }
    } elseif (isset($_POST['edit_batch_notice_form'])) {
        // Handle batch notice edit form submission
        $id = sanitize_input($conn, $_POST['id']);
        $batch_number = sanitize_input($conn, $_POST['batch_number']);
        $subject = sanitize_input($conn, $_POST['subject']);
        $added_date = sanitize_input($conn, $_POST['added_date']);
        $view_link = sanitize_input($conn, $_POST['view_link']);
        
        $query = "UPDATE `batch-notice` SET batch_number='$batch_number', subject='$subject', added_date='$added_date', view_link='$view_link' WHERE id='$id'";
        
        if (mysqli_query($conn, $query)) {
            // Redirect after updating
            header("Location: batch-notice.php?message=update");
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['delete_batch_notice_form'])) {
        // Handle batch notice delete form submission
        $id = sanitize_input($conn, $_POST['id']);
        
        $query = "DELETE FROM `batch-notice` WHERE id='$id'";
        
        if (mysqli_query($conn, $query)) {
            // Redirect after deleting
            header("Location: batch-notice.php?message=delete");
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
}


// End output buffering and flush output
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Delete Notice</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="add notice.css">
</head>
<body>
    <?php if ($message == 'update'): ?>
        <div class="alert alert-success">The Notice was updated successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The Notice was deleted successfully.</div>
    <?php endif; ?>

<div class="table">
    <h2>Batch Notices</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Batch Number</th>
                <th>Subject</th>
                <th>Added Date</th>
                <th>View Link</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($batch_notice as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['batch_number']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= htmlspecialchars($row['added_date']) ?></td>
                    <td><?= htmlspecialchars($row['view_link']) ?></td>
                    <td data-cell="Action"><button onclick="manageBatch(this.parentNode.parentNode)" class="manage-button view-link">Manage</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- The Modal -->
<div id="manageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit Batch Notice</h2>
        <form id="editForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="edit_batch_notice_form">
            <input type="hidden" id="edit_id" name="id">
            
            <label for="edit_batch_number">Batch Number:</label>
            <select id="edit_batch_number" name="batch_number" required>
                <?php foreach ($batch_numbers as $batch): ?>
                    <option value="<?= htmlspecialchars($batch) ?>"><?= htmlspecialchars($batch) ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="edit_subject">Subject:</label>
            <input type="text" id="edit_subject" name="subject" required>
            
            <label for="edit_added_date">Added Date:</label>
            <input type="date" id="edit_added_date" name="added_date" required>
            
            <label for="edit_view_link">Link:</label>
            <input type="text" id="edit_view_link" name="view_link">
            
            <input type="submit" value="Save Changes">
        </form>
        <form id="deleteForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="delete_batch_notice_form">
            <input type="hidden" id="delete_id" name="id">
            <input type="submit" value="Delete Notice">
        </form>
    </div>
</div>


<script>
    function manageBatch(row) {
        // Get data from the row
        var cells = row.getElementsByTagName('td');
        var id = cells[0].innerText;
        var batch_number = cells[1].innerText;
        var subject = cells[2].innerText;
        var added_date = cells[3].innerText;
        var view_link = cells[4].innerText;

        // Set data in the modal
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_batch_number').value = batch_number;
        document.getElementById('edit_subject').value = subject;
        document.getElementById('edit_added_date').value = added_date;
        document.getElementById('edit_view_link').value = view_link;
        document.getElementById('delete_id').value = id;

        // Display the modal
        document.getElementById('manageModal').style.display = "block";
    }


    function closeModal() {
        document.getElementById('manageModal').style.display = "none";
    }

    // Close the modal if the user clicks outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('manageModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
