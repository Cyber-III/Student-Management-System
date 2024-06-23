<?php
session_start();
include_once('connection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type']; // Added a new field to determine the user type

    if (empty($username) && empty($password)) {
        echo "<script>alert('Please Fill Username and Password'); window.location='index.php';</script>";
        exit;
    } elseif (empty($password)) {
        echo "<script>alert('Please Fill Password'); window.location='index.php';</script>";
        exit;
    } elseif (empty($username)) {
        echo "<script>alert('Please Fill Username'); window.location='index.php';</script>";
        exit;
    } else {
        // Determine the table based on user type
        $table = "";
        $redirectPath = "";
        if ($userType == 'user') {
            $table = "login_tbl";
            $redirectPath = 'welcome.php';
        } elseif ($userType == 'admin') {
            $table = "admin_login_tbl";
            $redirectPath = 'admin/welcome.php';
        } elseif ($userType == 'lecturer') {
            $table = "lecturers";
            $redirectPath = 'Lecturer/welcome.php';
        } else {
            echo "<script>alert('Invalid User Type'); window.location='index.php';</script>";
            exit;
        }

        $sql = "SELECT * FROM `$table` WHERE `username`='$username' AND `password`='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $name = $row['name'];
            $storedUsername = $row['username'];
            $storedPassword = $row['password'];

            if ($username == $storedUsername && $password == $storedPassword) {
                $_SESSION['name'] = $name;
                $_SESSION['username'] = $username;
                // Don't store password in session
                header('location: ' . $redirectPath);
                exit;
            }
        } else {
            echo "<script>alert('Invalid Username or Password'); window.location='index.php';</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="user_type" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="lecturer">Lecturer</option>
        </select>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
