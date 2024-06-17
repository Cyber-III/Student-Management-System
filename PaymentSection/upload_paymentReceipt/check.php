<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../admin/assests/content/static/template.php');

// Fetch all data from the payment_receipts table
$receipts_data = [];
$result = mysqli_query($conn, "SELECT * FROM payment_receipts");
while ($row = mysqli_fetch_assoc($result)) {
    $receipts_data[] = $row;
}

// Check if the request is to update the status
if (isset($_POST['receipt_id']) && isset($_POST['status'])) {
    $receipt_id = $_POST['receipt_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE payment_receipts SET status = 'checked' WHERE id = $receipt_id";
    if (mysqli_query($conn, $update_query)) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipts</title>

    <link rel="stylesheet" href="../../admin/style-template.css">
    <link rel="stylesheet" href="check.css">
    <script>
        function downloadImage(imagePath) {
            const link = document.createElement('a');
            link.href = imagePath;
            link.download = imagePath.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function updateStatus(receiptId) {
            if (confirm("Are you sure you accept this receipt?")) {
                // Create a new AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Send the receipt ID and new status to the server
                xhr.send("receipt_id=" + receiptId + "&status=checked");
            } else {
                // If user cancels, uncheck the checkbox
                document.getElementById("checkbox_" + receiptId).checked = false;
            }
        }
    </script>
</head>
<body>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Payment Date</th>
                    <th>Payment Amount</th>
                    <th>Receipt</th>
                    <th>Remark</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="library-tbody">
                <?php if ($result->num_rows > 0): ?>
                    <?php foreach ($receipts_data as $row): ?>
                        <?php if ($row['status'] != 'checked'): ?>
                            <tr id="row_<?= $row['id'] ?>">
                                <td><?= htmlspecialchars($row['student_id']) ?></td>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                <td><?= htmlspecialchars($row['payment_amount']) ?></td>
                                <td>
                                    <?php if (!empty($row['file_path'])): ?>
                                        <img style="cursor:pointer" src="<?= htmlspecialchars($row['file_path']) ?>" alt="Receipt Image" width="100" height="100" onclick="downloadImage('<?= htmlspecialchars($row['file_path']) ?>')">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['remark']) ?></td>
                                <td>
                                    <span id="status_<?= $row['id'] ?>"><?= htmlspecialchars($row['status']) ?></span>
                                    <input type="checkbox" id="checkbox_<?= $row['id'] ?>" onclick="updateStatus(<?= $row['id'] ?>)">
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
