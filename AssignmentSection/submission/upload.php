<?php
include_once('../../connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if module_id and assignment_file are set
    if (isset($_POST['module_id']) && isset($_FILES['assignment_file'])) {
        // Retrieve module ID
        $module_id = $_POST['module_id'];
        
        // Retrieve other necessary data from session
        session_start();
        $username = $_SESSION['username'];

        // Fetch user's batch number and course from login_tbl
        $sql_user = "SELECT batch_number, course FROM login_tbl WHERE username = ?";
        $stmt_user = $conn->prepare($sql_user);
        if ($stmt_user) {
            $stmt_user->bind_param('s', $username);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user = $result_user->fetch_assoc();
            $stmt_user->close();

            if ($user) {
                $batch_number = $user['batch_number'];
                $course = $user['course'];

                // File upload handling
                $file_name = $_FILES['assignment_file']['name'];
                $file_tmp = $_FILES['assignment_file']['tmp_name'];
                $file_size = $_FILES['assignment_file']['size'];
                $file_error = $_FILES['assignment_file']['error'];

                // Check for errors in file upload
                if ($file_error === 0) {
                    // Check file size (you can set your own limit)
                    if ($file_size <= 5242880) { // 5MB
                        // Generate a unique filename to prevent overwriting existing files
                        $file_destination = 'uploads/' . uniqid('', true) . '_' . $file_name;
                        
                        // Move uploaded file to destination directory
                        if (move_uploaded_file($file_tmp, $file_destination)) {
                            // File uploaded successfully
                            // Insert data into assignments table
                            $sql_insert = "INSERT INTO assignments (username, batch_number, module_code, course, module_name, date_of_issue, date_of_submit, file_path) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
                            $stmt_insert = $conn->prepare($sql_insert);
                            if ($stmt_insert) {
                                $module_code = ''; // You need to fetch this from assignment_schedule based on module_id
                                $module_name = ''; // You need to fetch this from assignment_schedule based on module_id
                                $date_of_issue = ''; // You need to fetch this from assignment_schedule based on module_id
                                $stmt_insert->bind_param('sssssss', $username, $batch_number, $module_code, $course, $module_name, $date_of_issue, $file_destination);
                                $stmt_insert->execute();
                                $stmt_insert->close();
                                
                                // Display a notification on the same page
                                echo '<div class="notification">File uploaded successfully and data saved.</div>';
                            } else {
                                echo "Failed to prepare SQL statement: " . $conn->error;
                            }
                        } else {
                            echo "Failed to move uploaded file.";
                        }
                    } else {
                        echo "File size exceeds limit.";
                    }
                } else {
                    echo "Error uploading file: " . $file_error;
                }
            } else {
                echo "User not found.";
            }
        } else {
            echo "Error in SQL query: " . $conn->error;
        }
    } else {
        echo "Missing module ID or file.";
    }
} else {
    // Redirect if accessed directly
    header("Location: ../assignment_schedule.php");
    exit();
}
?>
