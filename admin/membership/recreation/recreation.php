<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');

// Fetch all data from the library_mem table
$library_mem_data = [];
$result = mysqli_query($conn, "SELECT * FROM recreation_mem");
while ($row = mysqli_fetch_assoc($result)) {
    $library_mem_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recreation Membership</title>

    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-recreation.css">
</head>
<body>
    <h1 class="topic">Recreation Membership</h1>
    <br>
    <!-- Search bar -->
    <div class="search-bar">
        <label for="search">Search by Username:</label>
        <input type="text" id="search" placeholder="Enter Username">
        <button id="search-icon"><i class="fas fa-search"></i></button>
    </div>
    <br>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody id="library-tbody">
                <?php
                    if ($result->num_rows > 0) {
                        foreach ($library_mem_data as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "</tr>";
                        }
                    }

                    else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchIcon = document.getElementById('search-icon');
            const searchInput = document.getElementById('search');
            const rows = document.querySelectorAll('#library-tbody tr');

            function filterRows() {
                const searchQuery = searchInput.value.toLowerCase().trim();
                rows.forEach(row => {
                    const usernameCell = row.querySelector('td:nth-child(1)');
                    if (usernameCell.textContent.toLowerCase().includes(searchQuery)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchIcon.addEventListener('click', filterRows);
            searchInput.addEventListener('input', filterRows);
        });
    </script>
</body>
</html>
