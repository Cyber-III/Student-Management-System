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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="plan.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        
    </style>
    <script>
        function searchByBatch() {
            var batchNumber = document.getElementById('batch_number').value;
            if (batchNumber !== "") {
                $.ajax({
                    url: 'search_student.php',
                    type: 'GET',
                    data: { batch_number: batchNumber },
                    success: function(response) {
                        document.getElementById('exam-schedule-tbody').innerHTML = response;
                        addManageButtonListeners();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        }

        function searchByUsername() {
            var username = document.getElementById('username').value;
            if (username !== "") {
                $.ajax({
                    url: 'search_student.php',
                    type: 'GET',
                    data: { username: username },
                    success: function(response) {
                        document.getElementById('exam-schedule-tbody').innerHTML = response;
                        addManageButtonListeners();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        }

        function addManageButtonListeners() {
            document.querySelectorAll('.manage-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var row = this.closest('tr');
                    var id = this.getAttribute('data-id');
                    var username = row.children[0].textContent;
                    var nxtPayDate = row.children[1].textContent;
                    var description = row.children[2].textContent;
                    var amount = row.children[3].textContent;

                    document.getElementById('modal-username').value = username;
                    document.getElementById('modal-nxtPayDate').value = nxtPayDate;
                    document.getElementById('modal-description').value = description;
                    document.getElementById('modal-amount').value = amount;
                    document.getElementById('modal-id').value = id;

                    document.getElementById('manageModal').style.display = "block";
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            addManageButtonListeners();
        });

        function closeModal() {
            document.getElementById('manageModal').style.display = "none";
        }





    </script>
</head>
<body>
    <?php if ($message == 'insertpayment'): ?>
        <div class="alert alert-success">Payment was inserted successfully.</div>
    <?php elseif ($message == 'updatedpay'): ?>
        <div class="alert alert-success">Payment Records was updated successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">Book was deleted successfully.</div>
    <?php endif; ?>
    
    <form action="paymentsubmission.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="usernames">Username:</label>
                    <input type="text" id="usernames" name="usernames" required> <!-- making required the fields -->
                </div>

                <div class="form-group">
                    <label for="batch_no">Batch Number:</label>
                    <select id="batch_no" name="batch_no" required> <!-- making required the fields -->
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
                    <input type="date" id="date" name="date" required> <!-- making required the fields -->
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" required> <!-- making required the fields -->
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" id="amount" name="amount" required> <!-- making required the fields -->
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
            <label for="batch_number">Search by Batch Number:</label>
            <div class="input-group">
                <select id="batch_number" name="batch_number">
                    <option value="">Select Batch Number</option>
                    <?php foreach ($batch_numbers as $batch_number): ?>
                        <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="search-icon" onclick="searchByBatch()"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <div class="form-group">
            <label for="username">Search by Username:</label>
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Student's Username">
                <button id="search-icon" onclick="searchByUsername()"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
    <br>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Username</th>
                    <th>Next Payment Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Penalty</th>
                </tr>
            </thead>
            <tbody id="exam-schedule-tbody">
                
            </tbody>
        </table>
    </div>

    <!-- Modal -->
        <div id="manageModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Manage Payment</h2>
                </div>
                <div class="modal-body">
                    <form id="manageForm" action="update_delete_student.php" method="POST">
                        <input type="hidden" id="modal-id" name="id">
                        <div class="form-group">
                            <label for="modal-username">Username</label>
                            <input type="text" id="modal-username" name="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modal-nxtPayDate">Next Payment Date</label>
                            <input type="date" id="modal-nxtPayDate" name="nxtPayDate">
                        </div>
                        <div class="form-group">
                            <label for="modal-description">Description</label>
                            <input type="text" id="modal-description" name="description">
                        </div>
                        <div class="form-group">
                            <label for="modal-amount">Amount</label>
                            <input type="number" id="modal-amount" name="amount">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="action" value="delete" class="delete-link">Delete</button>
                            <button type="submit" name="action" value="update" class="view-link">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    <script>
        // Function to close the modal
        function closeModal() {
            document.getElementById('manageModal').style.display = "none";
        }

    </script>

</body>
</html>
