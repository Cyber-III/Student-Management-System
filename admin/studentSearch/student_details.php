<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Get the username from the query parameter
$username = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch student details based on the username
$student = null; // Initialize $student variable
if ($username) {
    $result = mysqli_query($conn, "SELECT * FROM login_tbl WHERE username = '$username'");
    $student = mysqli_fetch_assoc($result);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-view_profile.css">

</head>
<body>
<div class="container">
    <div class="side-container">
        <div class="profilepic">
            <label for="profile-image-input">
                <div id="profile-image-container">
                    <img id="profile-image-preview" src="Images/graduate.png" alt="User Profile Icon">
                </div>
            </label>
            <input type="file" id="profile-image-input" accept="image/*" style="display:none;">
            <button id="change-profile-image-button" type="button">Change Image</button>
        </div>
        <div class="uni-pic-container">
            <div class="box">
                <img src="Images/uni-1.png" alt="uni-1-pic">
            </div>
            <div class="box">
                <img src="Images/uni-2.png" alt="uni-1-pic">
            </div> 
        </div>
    </div>

    <div class="profileTop">
        <div class="button-container">
            <button id="editButton" class="manage-button view-link">Edit</button>
            <form id="deleteForm" action="delete_student.php" method="POST">
                <input type="hidden" name="username" value="<?= htmlspecialchars($student['username']) ?>">
                <button id="deleteButton" class="manage-button delete-link" type="submit" onclick="return confirm('Are you sure you want to delete this student?');">Delete</button>
            </form>
        </div>


        <?php if ($student): ?>
        <b><h1 class="topname"> <span><?= htmlspecialchars($student['student_name']) ?></span></h1></b>
        <h6><?= htmlspecialchars($student['course']) ?></h6>
        <br>
        <div class="p_data">
            <div class="personal_details">
                <h2>Personal Details</h2>
                <hr>
                <br>
                <div class="form-row">
                    <span class="form-label">Student ID:</span>
                    <span class="form-value"><?= htmlspecialchars($student['username']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Batch No:</span>
                    <span class="form-value"><?= htmlspecialchars($student['batch_number']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Gender:</span>
                    <span class="form-value"><?= htmlspecialchars($student['gender']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Date Of Birth:</span>
                    <span class="form-value"><?= htmlspecialchars($student['dob']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">NIC No:</span>
                    <span class="form-value"><?= htmlspecialchars($student['nic']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">E-mail:</span>
                    <span class="form-value"><?= htmlspecialchars($student['email']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Contact info:</span>
                    <span class="form-value"><?= htmlspecialchars($student['contact']) ?></span>
                </div>
            </div>
        </div>
        <br><br>
        <div class="University_data">
            <div class="uni_details">
                <h2>University Details</h2>
                <hr>
                <br>
                <div class="form-row">
                    <span class="form-label">Awarding University:</span>
                    <span class="form-value"><?= htmlspecialchars($student['awarding_uni']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">University Number:</span>
                    <span class="form-value"><?= htmlspecialchars($student['uni_number']) ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Education Center:</span>
                    <span class="form-value"><?= htmlspecialchars($student['lec']) ?></span>
                </div>
            </div>
        </div>
        <?php else: ?>
            <p>No student found with username <?= htmlspecialchars($username) ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal-background">
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Student Details</h2>
            <form id="editForm" action="update_student.php" method="POST">
                <input type="hidden" name="username" value="<?= htmlspecialchars($student['username']) ?>">

                <div class="form-row">
                    <label for="edit-student_name">Student Name:</label>
                    <input type="text" id="edit-student_name" name="student_name" value="<?= htmlspecialchars($student['student_name']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-course">Course:</label>
                    <input type="text" id="edit-course" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-batch_number">Batch No:</label>
                    <input type="text" id="edit-batch_number" name="batch_number" value="<?= htmlspecialchars($student['batch_number']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-gender">Gender:</label>
                    <input type="text" id="edit-gender" name="gender" value="<?= htmlspecialchars($student['gender']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-dob">Date Of Birth:</label>
                    <input type="date" id="edit-dob" name="dob" value="<?= htmlspecialchars($student['dob']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-nic">NIC No:</label>
                    <input type="text" id="edit-nic" name="nic" value="<?= htmlspecialchars($student['nic']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-email">E-mail:</label>
                    <input type="email" id="edit-email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-contact">Contact info:</label>
                    <input type="text" id="edit-contact" name="contact" value="<?= htmlspecialchars($student['contact']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-awarding_uni">Awarding University:</label>
                    <input type="text" id="edit-awarding_uni" name="awarding_uni" value="<?= htmlspecialchars($student['awarding_uni']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-uni_number">University Number:</label>
                    <input type="text" id="edit-uni_number" name="uni_number" value="<?= htmlspecialchars($student['uni_number']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="edit-lec">Education Center:</label>
                    <input type="text" id="edit-lec" name="lec" value="<?= htmlspecialchars($student['lec']) ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="view-link">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const profileImageInput = document.getElementById("profile-image-input");
    const profileImagePreview = document.getElementById("profile-image-preview");
    const changeProfileImageButton = document.getElementById("change-profile-image-button");
    const modal = document.getElementById("modal");
    const editButton = document.getElementById("editButton");
    const closeModalButton = document.querySelector(".close");

    changeProfileImageButton.addEventListener("click", function() {
        profileImageInput.click();
    });

    profileImageInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    const squareSize = Math.min(this.width, this.height);
                    canvas.width = squareSize;
                    canvas.height = squareSize;

                    const offsetX = (this.width - squareSize) / 2;
                    const offsetY = (this.height - squareSize) / 2;

                    ctx.drawImage(this, offsetX, offsetY, squareSize, squareSize, 0, 0, squareSize, squareSize);
                    profileImagePreview.src = canvas.toDataURL();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    editButton.addEventListener("click", function() {
        modal.style.display = "block";
    });

    closeModalButton.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
</script>

</body>
</html>
