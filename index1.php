<?php
session_start();
include_once('connection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-container img {
            max-width: 50%;
            height: auto;
            margin-bottom: 1rem;
        }

        .login-container .form-control {
            border-radius: 25px;
        }

        .login-container button {
            width: 100%;
            border-radius: 25px;
            background-color: #3333ff;
            border-color: #3333ff;
            color: #fff;
        }

        .login-container button:hover {
            background-color: #1a1aff;
            border-color: #1a1aff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Student Information System</h1>
        <img src="pics/login.png" alt="Login Image">
        <form action="" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <select name="user_type" class="form-select" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="lecturer">Lecturer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
    </div>
</body>
</html>
