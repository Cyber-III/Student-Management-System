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
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="viewprofile.css">
</head>
<body>
<div class="container">
    <!-- Adding user profile image -->
    <div class="side-container">
        <div class="profilepic">
            <label for="profile-image-input">
                <div id="profile-image-container">
                    <img id="profile-image-preview" src="..\adminViewProfile\Images\graduate.png" alt="User Profile Icon">
                </div>
            </label>
            <input type="file" id="profile-image-input" accept="image/*" style="display:none;">
            <button id="change-profile-image-button" type="button">Change Image</button>
        </div>
        <div class="uni-pic-container">
            <div class="box">
                <img src="..\adminViewProfile\Images\uni-1.png" alt="uni-1-pic">
            </div>
            <div class="box">
                <img src="..\adminViewProfile\Images\uni-2.png" alt="uni-2-pic">
            </div>
        </div>
    </div>

    <div class="profileTop">
        <b><h1 class="topname"><span><?php echo isset($student_name) ? htmlspecialchars($student_name) : ''; ?></span></h1></b>
        <h6><?php echo isset($course) ? htmlspecialchars($course) : ''; ?></h6>
        <br>
        <div class="p_data">
            <div class="personal_details">
                <h2>Admin Details</h2>
                <hr>
                <br>
                <div class="form-row">
                    <span class="form-label">Admin Name :</span>
                    <span class="form-value"><?php echo isset($name) ? htmlspecialchars($name) : ''; ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Gender :</span>
                    <span class="form-value"><?php echo isset($gender) ? htmlspecialchars($gender) : ''; ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Username :</span>
                    <span class="form-value"><?php echo isset($username) ? htmlspecialchars($username) : ''; ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Password (sample data):</span>
                    <span class="form-value"><?php echo isset($password) ? htmlspecialchars($password) : ''; ?></span>
                </div>
            </div>
        </div>
        <br><br>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const profileImageInput = document.getElementById("profile-image-input");
        const profileImagePreview = document.getElementById("profile-image-preview");
        const changeProfileImageButton = document.getElementById("change-profile-image-button");

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
    });
</script>
</body>
</html>
