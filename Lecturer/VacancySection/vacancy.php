<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacancies</title>

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-vacancy.css">
</head>
<body>

<div class="content">
    <?php
    // Display success message if present
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="position-fixed top-50 start-50 translate-middle alert-container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    You submitted the details successfully. Our admins will contact you as soon as possible.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>';
    }

    // Display already applied message if present
    if (isset($_GET['already_applied']) && $_GET['already_applied'] == 1) {
        echo '<div class="position-fixed top-50 start-50 translate-middle alert-error-container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    You have already applied for this job post. Please wait. Our admins will contact you as soon as possible.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>';
    }

    // Fetch data from the vacancy_tbl
    $sql = "SELECT * FROM vacancy_tbl";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            // Assuming you have columns 'title', 'content', 'image'
            echo '<a class="job-card">';
            echo '<img class="job-img" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="">';
            echo '<div class="job-info">';
            echo '<h5 class="job-title">' . htmlspecialchars($row['title']) . '</h5>';
            echo '<p class="job-description">' . htmlspecialchars($row['content']) . '</p>';
            echo '<div class="btn-container">';
            echo '<button class="apply-btn" onclick="openModal(' . $row['id'] . ', \'' . htmlspecialchars($row['title']) . '\')">Apply</button>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
        }
    } else {
        echo "<p>No vacancies found.</p>";
    }

    // Close connection
    mysqli_close($conn);
    ?>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form class="form-card" action="apply.php" method="POST">
            <!-- Add hidden fields for job_id and job_title -->
            <input type="hidden" id="job_id" name="job_id" value="">
            <input type="hidden" id="job_title" name="job_title" value="">

            <div class="row justify-content-between text-left">
                <div class="form-content form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Student ID<span class="text-danger"> *</span></label>
                    <input type="text" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" id="id" name="id" placeholder="Enter Your ID" readonly>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Student Name<span class="text-danger"> *</span></label>
                    <input type="text" value="<?php echo isset($student_name) ? htmlspecialchars($student_name) : ''; ?>" id="name" name="name" placeholder="Enter Your Name" readonly>
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Batch Number<span class="text-danger"> *</span></label>
                    <input type="text" value="<?php echo isset($batch_number) ? htmlspecialchars($batch_number) : ''; ?>" id="bajnum" name="bajnum" placeholder="Enter Your Batch Number" readonly>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Gender<span class="text-danger"> *</span></label>
                    <input type="text" value="<?php echo isset($gender) ? htmlspecialchars($gender) : ''; ?>" id="gender" name="gender" placeholder="Enter Your Gender" readonly>
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Email Address<span class="text-danger"> *</span></label>
                    <input type="text" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" id="email" name="email" placeholder="Enter Your Email" readonly>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <label class="form-control-label px-3">Contact Number<span class="text-danger"> *</span></label>
                    <input type="text" value="<?php echo isset($contact) ? htmlspecialchars($contact) : ''; ?>" id="mob" name="mob" placeholder="Enter Your Contact Number" readonly>
                </div>
            </div>

            <div class="form-group col-sm-6">
                <button type="submit" class="btn-block btn-primary">Apply</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Function to open the modal
    function openModal(jobId, jobTitle) {
        document.getElementById("job_id").value = jobId;
        document.getElementById("job_title").value = jobTitle;
        modal.style.display = "block";
    }

    // Function to close the modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Close the modal when user clicks outside of the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<script>
    $(document).ready(function() {
        // Function to dismiss the alert message after a certain duration
        setTimeout(function() {
            $(".alert-container").fadeOut('slow'); // Adjust animation as per your preference
        }, 20000); // Adjust the duration as per your preference

        // Close alert message when the close button is clicked
        $(".btn-close").click(function() {
            $(".alert-container").fadeOut('slow'); // Adjust animation as per your preference
        });

        setTimeout(function() {
            $(".alert-error-container").fadeOut('slow'); // Adjust animation as per your preference
        }, 20000); // Adjust the duration as per your preference

        // Close alert message when the close button is clicked
        $(".btn-close").click(function() {
            $(".alert-error-container").fadeOut('slow'); // Adjust animation as per your preference
        });
    });
</script>

</body>
</html>
