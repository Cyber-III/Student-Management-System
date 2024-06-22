<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch awarding batch numbers
$batch_numbers = [];
$result2 = mysqli_query($conn, "SELECT DISTINCT batch_no FROM batches");
while ($row = mysqli_fetch_assoc($result2)) {
    $batch_numbers[] = $row['batch_no'];
}

// Fetch payment records
$payment_records = [];
$result3 = mysqli_query($conn, "SELECT * FROM make_payment_tbl");
while ($row = mysqli_fetch_assoc($result3)) {
    $payment_records[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="plan.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.manage-button').click(function() {
                var username = $(this).data('username');
                var batch_number = $(this).data('batch-number');
                var no = $(this).data('no');
                var nxt_pay_date = $(this).data('nxt-pay-date');
                var description = $(this).data('description');
                var amount = $(this).data('amount');
                var penalty = $(this).data('penalty');

                $('#manageModal #username').val(username);
                $('#manageModal #batch_number').val(batch_number);
                $('#manageModal #no').val(no);
                $('#manageModal #nxt_pay_date').val(nxt_pay_date);
                $('#manageModal #description').val(description);
                $('#manageModal #amount').val(amount);
                $('#manageModal #penalty').val(penalty);
                $('#manageModal').show();
            });

            $('#editButton').click(function() {
                $('#manageForm').attr('action', 'update_payment.php');
                $('#manageForm').submit();
            });

            $('#deleteButton').click(function() {
                if (confirm('Are you sure you want to delete this record?')) {
                    $('#manageForm').attr('action', 'delete_payment.php');
                    $('#manageForm').submit();
                }
            });

            $('.close').click(function() {
                $('#manageModal').hide();
            });
        });

        function searchByBatch() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("batch_number_search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Start from index 1 to skip header row
                td = tr[i].getElementsByTagName("td")[1]; // Index 1 corresponds to batch_number column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        function searchByUsername() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("username_search");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Start from index 1 to skip header row
                td = tr[i].getElementsByTagName("td")[0]; // Index 0 corresponds to username column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
    </script>
</head>
<body>
    <div class="main_container">
    <?php if ($message == 'insertpayment'): ?>
        <div class="alert alert-success">Payment was inserted successfully.</div>
    <?php elseif ($message == 'updatedpay'): ?>
        <div class="alert alert-success">Payment Records were updated successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">Payment Records were deleted successfully.</div>
    <?php endif; ?>
    
    <form action="paymentsubmission.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="usernames">Username:</label>
                    <input type="text" id="usernames" name="usernames" required>
                </div>

                <div class="form-group">
                    <label for="batch_no">Batch Number:</label>
                    <select id="batch_no" name="batch_no" required>
                        <option value="">Select Batch Number</option>
                        <?php foreach ($batch_numbers as $batch_number): ?>
                            <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="paymentID">Payment ID:</label>
                    <input type="number" id="paymentID" name="paymentID" required>
                </div>

                <div class="form-group">
                    <label for="date">Payment Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" required>
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" id="amount" name="amount" required>
                </div>
            </div>

            <br>
            <button type="submit" class="view-link">Submit</button>
        </div>
        <br>
        <br>
    </form>

    <!-- Search bar -->
    <div class="form-row">
        <div class="form-group">
            <label for="batch_number_search">Search by Batch Number:</label>
            <div class="input-group">
                <select id="batch_number_search" name="batch_number_search" onchange="searchByBatch()">
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="search-icon" class="search-button" onclick="searchByBatch()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="username_search">Search by Username:</label>
            <div class="input-group">
                <input type="text" id="username_search" name="username_search" placeholder="Student's Username" onkeyup="searchByUsername()">
                <button type="button" id="search-icon" class="search-button" onclick="searchByUsername()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <br>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Batch Number</th>
                <th>No</th>
                <th>Next Pay Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Penalty</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payment_records as $record): ?>
                <tr style="display: none;"> <!-- Initially hide all rows -->
                    <td data-cell="Username"><?= htmlspecialchars($record['username']) ?></td>
                    <td data-cell="Batch Number"><?= htmlspecialchars($record['batch_number']) ?></td>
                    <td data-cell="No"><?= htmlspecialchars($record['no']) ?></td>
                    <td data-cell="Next Pay Date"><?= htmlspecialchars($record['nxt_pay_date']) ?></td>
                    <td data-cell="Description"><?= htmlspecialchars($record['description']) ?></td>
                    <td data-cell="Amount"><?= htmlspecialchars($record['amount']) ?></td>
                    <td data-cell="Penalty"><?= htmlspecialchars($record['penalty']) ?></td>
                    <td data-cell="Action">
                        <button class="manage-button view-link" data-username="<?= htmlspecialchars($record['username']) ?>" data-batch-number="<?= htmlspecialchars($record['batch_number']) ?>" data-no="<?= htmlspecialchars($record['no']) ?>" data-nxt-pay-date="<?= htmlspecialchars($record['nxt_pay_date']) ?>" data-description="<?= htmlspecialchars($record['description']) ?>" data-amount="<?= htmlspecialchars($record['amount']) ?>" data-penalty="<?= htmlspecialchars($record['penalty']) ?>">Manage</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- The Modal -->
    <div id="manageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="manageForm" method="POST">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>
                        </div>

                        <div class="form-group">
                            <label for="batch_number">Batch Number:</label>
                            <input type="text" id="batch_number" name="batch_number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="no">No:</label>
                            <input type="number" id="no" name="no" required>
                        </div>

                        <div class="form-group">
                            <label for="nxt_pay_date">Next Pay Date:</label>
                            <input type="date" id="nxt_pay_date" name="nxt_pay_date" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" id="description" name="description" required>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="text" id="amount" name="amount" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="penalty">Penalty:</label>
                            <input type="text" id="penalty" name="penalty" required>
                        </div>
                    </div>

                    <br>
                    <button type="button" id="editButton" class="view-link">Edit</button>
                    <button type="button" id="deleteButton" class="delete-link">Delete</button>
                </div>
                <br>
                <br>
            </form>
        </div>
    </div>
</body>
</html>
