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
    
    




    <div class="container-box">
        <div class="image-container">
            <img src="pics/receipt.png" class="img-fluid" alt="Message">
        </div>
        <div class="box form-box">
            <form action="submit_payment_receipt.php" method = "POST" enctype="multipart/form-data">
                <div class="field input">
                    <label for="id">Studnet ID</label>
                    <input type="text" id="id" name="student_id" placeholder="Your username" required>
                </div>
                <div class="field input">
                    <label for="st_name">Studnet Name</label>
                    <input type="text" id="st_name" name="student_name" placeholder="Your name" required>
                </div>
                <div class="field input">
                    <label for="pay_date">Payment Date</label>
                    <input type="date" id="pay_date" name="payment_date" required>
                </div>
                <div class="field input">
                    <label for="amount">Payment Amount</label>
                    <input type="number" id="amount" name="payment_amount" required>
                </div>
                <div class="field input">
                    <label for="user_avatar">Upload File:</label>
                    <input type="file" id="user_avatar" name="file"  required>
                
                </div>
                
                <div class="field input">
                    <label for="message" >Remark</label>
                    <textarea id="message" name="remark" rows="4" placeholder="Leave a comment..."></textarea>

                </div>     
                <div class="field-btn">
                    <input type="submit" class= "btn1" name="submit" value="Submit" required>
                    <input type="reset"  class ='btn1' name="cancel" value="Cancel" required> 


                </div>
                    
            </form>
        </div>
    </div>

   

    <div class="container-box">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Payment Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
               
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $record_id = $row['id']; // Use record id to uniquely identify the record
                        echo "<tr>";
                        echo "<td data-cell = 'Payment ID'>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td data-cell = 'Payment Date'>" . htmlspecialchars($row['payment_date']) . "</td>";
                        echo '<td data-cell = "Course Fee"> Course Fee </td>';
                        echo "<td data-cell = 'Payment Amount'>" . htmlspecialchars($row['payment_amount']) . "</td>";
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
    </div>
   

                

    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.js"></script>
    
</body>


</html>
