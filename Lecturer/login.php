<?php
session_start();
include_once('connection.php');

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) && empty($password)) {
        $_SESSION['error'] = 'Please fill in both username and password';
        header('Location: index.php');
        exit;
    } elseif (empty($password)) {
        $_SESSION['error'] = 'Please fill in the password';
        header('Location: index.php');
        exit;
    } elseif (empty($username)) {
        $_SESSION['error'] = 'Please fill in the username';
        header('Location: index.php');
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
            $_SESSION['error'] = 'Invalid username or password';
            header('Location: index.php');
            exit;
        }
    }

}
