<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Fetch data from the table
$username = $_SESSION['username']; // Assuming you have stored the username in the session
$sql = "SELECT * FROM payment_receipts WHERE student_id = ?";
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
    <title>Document</title>
    <link rel="stylesheet" href="../../style-template.css">
    <!-- Template File CSS -->
    <link rel="stylesheet" href="style-upload_paymentReceipt.css"> <!--Relevant PHP File CSS-->

    <!-- Tailwind CSS (required for Flowbite) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- Flowbite CSS -->
    <link href="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-green-500 text-white p-4 rounded shadow-md">
                Data submitted successfully.
            </div>
        </div>
        <script>
            // Remove the alert after 5 seconds
            setTimeout(() => {
                document.querySelector('.fixed.inset-0').remove();
            }, 5000);
        </script>
    <?php endif; ?>

    <section class="vh-100">
        <div class="container mx-auto h-full flex flex-col lg:flex-row items-center justify-center">
            <div class="w-full lg:w-1/2 p-4 flex justify-center">
                <img src="pics/receipt.png" class="img-fluid" alt="Message">
            </div>
            <div class="w-full lg:w-1/2 p-4 mt-8 lg:mt-0">
                <form class="form lg:mt-14 max-w-lg mx-auto" action="submit_payment_receipt.php" method="POST" enctype="multipart/form-data">
                    <label for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student ID</label>
                    <input type="text" id="id" name="student_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your username" required>
                    <br>

                    <? echo $username ?>

                    <label for="st_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student Name</label>
                    <input type="text" id="st_name" name="student_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your name" required>
                    <br>

                    <label for="pay_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment Date</label>
                    <input type="date" id="pay_date" name="payment_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600" required>
                    <br>

                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment Amount</label>
                    <input type="number" id="amount" name="payment_amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Amount" required>
                    <br>

                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Upload file</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="user_avatar" name="file" type="file" required>
                    <br>

                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remark</label>
                    <textarea id="message" name="remark" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                    <br>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary me-2">Cancel</button>
                </form>
            </div>
        </div>
    </section>


    <div class="table lg:ml-80">
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Payment Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $record_id = $row['id']; // Use record id to uniquely identify the record
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['payment_date']) . "</td>";
                    echo '<td> Course Fee </td>';
                    echo "<td>" . htmlspecialchars($row['payment_amount']) . "</td>";
                    echo '<td class="view-link">' . htmlspecialchars($row['status']) . "</td>";
                    
                    echo "</tr>";
                }
            } 
            else {
                echo "<tr><td colspan='5'>No payments found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>



    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.js"></script>
</body>
</html>
