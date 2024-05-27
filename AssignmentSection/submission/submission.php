<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');
// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // File upload
    $file_path = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = basename($_FILES["file"]["name"]);
        
        // Use an absolute path for the uploads directory
        $target_dir = __DIR__ . "/uploads/"; 
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a PDF
        if ($file_type == "pdf") {
            // Create the uploads directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $file_path = "uploads/" . $file_name;

                // Update database with file path
                $stmt = $conn->prepare("UPDATE assignment SET file_path = ? WHERE id = ?");
                $stmt->bind_param("si", $file_path, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "Only PDF files are allowed.";
        }
    }
}

// Fetch data from the table
$username = $_SESSION['username']; // Assuming you have stored the username in the session
$sql = "SELECT * FROM assignment WHERE student_id = ?";
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
    <title>Assignment Submission</title>
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
                <form class="form lg:mt-14 max-w-lg mx-auto" action="" method="POST" enctype="multipart/form-data">
                    <label for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assignment ID</label>
                    <input type="text" id="id" name="id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Assignment ID" required>
                    <br>

                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Upload PDF</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file" name="file" type="file" required>
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
                <th>Assignment ID</th>
                <th>Course</th>
                <th>Module Name</th>
                <th>Module Code</th>
                <th>Date</th>
                <th>Duration</th>
                <th>Assignments</th>
                <th>File</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['module_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['module_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['duration']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['assignments']) . "</td>";
                    echo "<td>";
                    if ($row['file_path']) {
                        echo "<a href='" . htmlspecialchars($row['file_path']) . "'>Download</a>";
                    } else {
                        echo "No file";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No assignments found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>

    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.js"></script>
</body>
</html>
