<?php
session_start();
include_once('connection.php');

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) && empty($password)) {
        echo "<script>alert('Please Fill Username and Password'); window.location='index.php';</script>";
        exit;
    }
    elseif (empty($password)) {
        echo "<script>alert('Please Fill Password'); window.location='index.php';</script>";
        exit;
    }
    elseif (empty($username)) {
        echo "<script>alert('Please Fill Username'); window.location='index.php';</script>";
        exit;
    }
    else {
        $sql = "SELECT * FROM `lecturers` WHERE `username`='$username' AND `password`='$password'";
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
                header('location: welcome.php');
                exit;
            }
        } else {
            echo "<script>alert('Invalid Username or Password'); window.location='index.php';</script>";
            exit;
        }
    }

}
