<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Insert data into database
    $sql = "INSERT INTO call_center_details (tel, email, address) VALUES ('$tel', '$email', '$address')";
    if (mysqli_query($conn, $sql)) {
        echo "Details added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Call Center Details</title>
    
    <!--c-center Css-->
    <link rel="stylesheet" href="c-center.css">
    <!-- Include your CSS and JS files -->
    <link rel="stylesheet" href="../style-template.css">


</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <h1>Edite call center Details</h1>
        <label for="tel">Tel:</label>
        <input type="text" id="tel" name="tel" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
