<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $sid = htmlspecialchars($_POST['sid']);
    $sname = htmlspecialchars($_POST['sname']);
    $password = htmlspecialchars($_POST['password']);
    $batch_number = htmlspecialchars($_POST['batch_number']);
    $course = htmlspecialchars($_POST['course']);
    $bdate = htmlspecialchars($_POST['bdate']);
    $nic = htmlspecialchars($_POST['nic']);
    $email = htmlspecialchars($_POST['email']);
    $contact = htmlspecialchars($_POST['contact']);
    $award_uni = htmlspecialchars($_POST['award_uni']);
    $uni_num = htmlspecialchars($_POST['uni_num']);
    $location = htmlspecialchars($_POST['location']);
    $gender = htmlspecialchars($_POST['gender']);

    // Prepare SQL insert statement for login_tbl
    $sql1 = "INSERT INTO login_tbl (student_name, username, password, course, batch_number, gender, dob, nic, email, contact, awarding_uni, uni_number, lec) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare SQL insert statement for payment_summary_tbl
    $sql2 = "INSERT INTO payment_summary_tbl (username, tot_course_fee, amount_paid, outstanding) 
            VALUES (?, 0, 0, 0)";
    
    // Initialize prepared statement for login_tbl
    if ($stmt1 = $conn->prepare($sql1)) {
        // Bind parameters for login_tbl
        $stmt1->bind_param("sssssssssssss", $sname, $sid, $password, $course, $batch_number, $gender, $bdate, $nic, $email, $contact, $award_uni, $uni_num, $location);
        
        // Execute the statement for login_tbl
        if ($stmt1->execute()) {
            // Initialize prepared statement for payment_summary_tbl
            if ($stmt2 = $conn->prepare($sql2)) {
                // Bind parameters for payment_summary_tbl
                $stmt2->bind_param("s", $sid);
                
                // Execute the statement for payment_summary_tbl
                if ($stmt2->execute()) {
                    header("Location: studentSearch.php?message=insertstudent");
                    exit();
                } else {
                    echo "Error inserting into payment_summary_tbl: " . $stmt2->error;
                }

                // Close the statement for payment_summary_tbl
                $stmt2->close();
            } else {
                echo "Error preparing statement for payment_summary_tbl: " . $conn->error;
            }
        } else {
            echo "Error inserting into login_tbl: " . $stmt1->error;
        }

        // Close the statement for login_tbl
        $stmt1->close();
    } else {
        echo "Error preparing statement for login_tbl: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
