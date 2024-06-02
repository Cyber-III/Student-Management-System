<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Fetch data for dropdown menus
$courses = [];
$modules = [];
$batch_numbers = [];

// Fetch courses and batch numbers from login_tbl
$result = mysqli_query($conn, "SELECT DISTINCT course, batch_number FROM login_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
    $batch_numbers[] = $row['batch_number'];
}
$courses = array_unique($courses);
$batch_numbers = array_unique($batch_numbers);

// Fetch module names, codes, and courses from modules
$result = mysqli_query($conn, "SELECT module_name, module_code, course FROM modules");
while ($row = mysqli_fetch_assoc($result)) {
    $modules[] = $row;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $module_code = mysqli_real_escape_string($conn, $_POST['module_code']);
    $batch_number = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $hours = mysqli_real_escape_string($conn, $_POST['hours']);
    $allow_submission = isset($_POST['allow_submission']) ? 1 : 0;

    // Insert the data into the exam_schedule table
    $sql = "INSERT INTO exam_schedule (course, module_name, module_code, batch_number, exam_name, date, time, location, hours, allow_submission) VALUES ('$course', '$module_name', '$module_code', '$batch_number', '$exam_name', '$date', '$time', '$location', '$hours', '$allow_submission')";

    if (mysqli_query($conn, $sql)) {
        // Set a success message in the session
        $_SESSION['success_message'] = "New exam added successfully";
        // Redirect to the exam_schedule.php page
        header("Location: exam_schedule.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all data from the exam_schedule table
$exam_schedule_data = [];
$result = mysqli_query($conn, "SELECT * FROM exam_schedule");
while ($row = mysqli_fetch_assoc($result)) {
    $exam_schedule_data[] = $row;
}

// Close the connection
mysqli_close($conn);
?>
