<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lec_name = $_POST['lec_name'];
    $title = $_POST['title'];
    $evaluation = $_POST['evaluation'];
    
    // Get username from session
    $username = $_SESSION['username']; // Assuming 'username' is stored in session after login

    // Insert data into lecture_evaluation table
    $sql = "INSERT INTO lecture_evaluation (lec_name, username, title, evaluation) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $lec_name, $username, $title, $evaluation);

    if ($stmt->execute()) {
        echo "Evaluation submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch lecturer names from the database
$sql = "SELECT name FROM lecturers";
$result = $conn->query($sql);
$lecturers = [];
while ($row = $result->fetch_assoc()) {
    $lecturers[] = $row['name'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-evaluation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Lecture Evaluation</title>
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/feedback.png" class="img-fluid custom-img" alt="feedback">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form method="POST" action="">
                    <div class="form-outline mb-4">
                        <label for="lec_name" class="form-label">Send Message To</label>
                        <select class="form-select" id="lec_name" name="lec_name" required>
                            <option selected disabled>Select lecturer</option>
                            <?php foreach ($lecturers as $lecturer): ?>
                                <option value="<?= htmlspecialchars($lecturer) ?>"><?= htmlspecialchars($lecturer) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
                    </div>
                    
                    <div class="form-outline mb-4">
                        <label for="evaluation" class="form-label">Evaluation</label>
                        <textarea class="form-control" id="evaluation" name="evaluation" rows="6" placeholder="Enter your evaluation" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('cancelButton').addEventListener('click', function() {
        document.querySelector('form').reset(); // Reset the form
    });
</script>
</body>
</html>
