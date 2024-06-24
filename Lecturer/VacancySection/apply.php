<?php
// Include the database connection
include_once('../connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data from the form
    $student_id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $batch_number = isset($_POST['bajnum']) ? $_POST['bajnum'] : null;
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $contact = isset($_POST['mob']) ? $_POST['mob'] : null;
    $job_id = isset($_POST['job_id']) ? $_POST['job_id'] : null;

    // Retrieve job ID and title from vacancy_tbl
    $sql_vacancy = "SELECT id, title FROM vacancy_tbl WHERE id = ?";
    $stmt_vacancy = $conn->prepare($sql_vacancy);
    $stmt_vacancy->bind_param("i", $job_id);
    $stmt_vacancy->execute();
    $result_vacancy = $stmt_vacancy->get_result();

    if ($result_vacancy->num_rows > 0) {
        $row_vacancy = $result_vacancy->fetch_assoc();
        $job_id = $row_vacancy['id'];
        $job_title = $row_vacancy['title'];

        // Check if the student has already applied for this job
        $sql_check_apply = "SELECT * FROM vacancy_apply_tbl WHERE job_id = ? AND student_id = ?";
        $stmt_check_apply = $conn->prepare($sql_check_apply);
        $stmt_check_apply->bind_param("is", $job_id, $student_id);
        $stmt_check_apply->execute();
        $result_check_apply = $stmt_check_apply->get_result();

        if ($result_check_apply->num_rows > 0) {
            // Student has already applied for this job
            header("Location: vacancy.php?already_applied=1");
            exit();
        } else {
            // Prepare an SQL statement to insert the data into vacancy_apply_tbl
            $sql_insert_apply = "INSERT INTO vacancy_apply_tbl (job_id, job_title, student_id, name, batch_num, gender, email, contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert_apply = $conn->prepare($sql_insert_apply);

            // Check if the statement was prepared successfully
            if ($stmt_insert_apply) {
                // Bind parameters and execute the statement
                $stmt_insert_apply->bind_param("isssssss", $job_id, $job_title, $student_id, $name, $batch_number, $gender, $email, $contact);
                if ($stmt_insert_apply->execute()) {
                    // Redirect to vacancy.php with success message
                    header("Location: vacancy.php?success=1");
                    exit();
                } else {
                    echo "Error: " . $stmt_insert_apply->error;
                }

                // Close the statement
                $stmt_insert_apply->close();
            } else {
                echo "Error: Unable to prepare SQL statement.";
            }
        }

        // Close the check statement
        $stmt_check_apply->close();
    } else {
        echo "Error: Job not found.";
    }

    // Close the statement and connection
    $stmt_vacancy->close();
    $conn->close();
} else {
    echo "Error: Form is not submitted.";
}
?>
