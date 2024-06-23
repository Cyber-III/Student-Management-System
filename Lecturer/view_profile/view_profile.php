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
    <link rel="stylesheet" href="style-view_profile.css">
</head>
<body>
<div class="container">
        <!-- //adding user profile image -->
    <div class="side-container">
        <div class="profilepic">
            <label for="profile-image-input">
                <div id="profile-image-container">
                    <img id="profile-image-preview" src="..\view_profile\Images\graduate.png" alt="User Profile Icon">
                </div>
            </label>
            <input type="file" id="profile-image-input" accept="image/*" style="display:none;">
            <button id="change-profile-image-button" type="button">Change Image</button>
        </div>
        
    </div>
    

    <div class="profileTop">

        <b><h1 class="topname"> <span><?php echo isset($name) ? $name : ''; ?></span></h1></b>
        <h6><?php echo isset($course) ? $course : ''; ?></h6>
        <br>
            
            <div class="p_data">
                <div class = "personal_details">
                    <h2> Personal Details</h2>
                    <hr>
                    <br>
                    <div class = "form-row">
                        <span class="form-label">Lecturer ID:</span>
                        <span class="form-value"><?php echo isset($username) ? $username : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">Gender:</span>
                        <span class="form-value"><?php echo isset($gender) ? $gender : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label">Date Of Birth:</span>
                        <span class="form-value"><?php echo isset($dob) ? $dob : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label"> NIC No:</span>
                        <span class="form-value"><?php echo isset($nic) ? $nic : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label"> E-mail:</span>
                        <span class="form-value"><?php echo isset($email) ? $email : ''; ?></span>
                    </div>
                    <div class = "form-row">
                        <span class="form-label"> Contact info:</span>
                        <span class="form-value"><?php echo isset($contact) ? $contact : ''; ?></span>
                    </div>
                </div>
               

            </div>
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