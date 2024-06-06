<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

$username = $_SESSION['username']; // Assuming you have stored the username in the session
$sql = "SELECT * FROM reserved_books WHERE username = ?";
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
    <title>Manage Reserved Books</title>
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-manageReservedBooks.css"> <!--Relevant PHP File CSS-->
</head>
<body>
    
    <div class="container">
        <div class="table-container-2">
            <div class="table">
                <table>
                    <tr>
                        <th>Book</th>
                        <th>Book Name</th>
                        <th>Image</th>
                        <th>Reserved Date</th>
                        <th>Order Cancel</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-label='Book ID'>" . htmlspecialchars($row['book_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td data-label='Book Name'>" . htmlspecialchars($row['book_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo '<td data-label="Book Image"><img src="data:image/jpeg;base64,' . base64_encode($row['book_image']) . '" width="100" height="100"/></td>';
                            echo "<td data-label='Reserved Date'>" . htmlspecialchars($row['reserved_date'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo '<td data-label="Cancel Order">';
                            echo '<form action="delete_reservation.php" method="post" onsubmit="return confirm(\'Are you sure you want to cancel this reservation?\');">';
                            echo '<input type="hidden" name="book_id" value="' . htmlspecialchars($row['book_id'], ENT_QUOTES, 'UTF-8') . '">';
                            echo '<button type="submit" class="view-link">CANCEL</button>';
                            echo '</form>';
                            echo '</td>';
                            echo "</tr>";

                        }
                    } else {
                        echo "<tr><td colspan='5'>No reserved books found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
