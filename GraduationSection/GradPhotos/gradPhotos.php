<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Load the HTML template
include_once('../../assests/content/static/template.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduation Photo Packages</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style-template.css"> <!-- Template File CSS -->
    <link rel="stylesheet" href="style-gradPhotos.css"> <!-- Relevant PHP File CSS -->
</head>
<body>

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-center h-100">
        <span1><h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">Select Your Photo Package</h1></span1>
            <div class="col-md-7 col-lg-5 col-xl-5">
                <div class="Rectangle p-4">
                    <!--Table_-->
                    <table id="table" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>Photo Package</th>
                                <th>Description</th>
                                <th>Fee</th>
                                <th>Sample</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Package 01</td>
                                <td>Stage Picture - 01 (Printed Copy), Single Portrait - 01 (Printed Copy), Family Picture - 01 (Printed Copy), Group Picture (Soft copy)</td>
                                <td>Rs. 8500</td>
                                <td>
                                    <style>
                                        .image-container:hover img{
                                            transform: scale(2.5);
                                            transition: transform 0.3s ease;
                                        }
                                    </style>
                                    <div class="image-container">
                                        <img src="pics/pakage2023.png" style="width: 100px; height: auto;">
                                    </div>
                                </td>
                                <td><form><input type="radio" name="sample" value="sample"></form></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="center-text"><p>Students who do not need the photo package mentioned above can buy the individual items from the list below.
                    Students who have selected the photo package also can use this section to purchase any additional item.</p></div>

                    <!--Table_1-->
                    <table id="table1" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Fee</th>
                                <th>Sample</th>
                                <th>Copies</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Stage Picture</td>
                                <td>Stage Picture - Size (10X12)</td>
                                <td>Rs. 3000</td>
                                <td>
                                    <style>
                                        .image-container:hover img{
                                            transform: scale(2.5);
                                            transition: transform 0.3s ease;
                                        }
                                    </style>
                                    <div class="image-container">
                                        <img src="pics/Stage.jpg" style="width: 100px; height: auto;">
                                    </div>
                                </td>
                                <td>
                                <style>
                                        .copies-input{
                                            width: 40px;
                                            text-align: center;
                                        }
                                    </style>
                                    <form><input type="text" name="copies" value="1" class="copies-input" style="width: 40px;" class="copies-input"></form>
                                </td>
                                <td><form><input type="checkbox" name="sample" value="sample"></form></td>
                            </tr>
                            <tr>
                                <td>Studio Portrait</td>
                                <td>Studio Portrait - Size (10X12)</td>
                                <td>Rs. 2500</td>
                                <td>
                                    <style>
                                        .image-container:hover img{
                                            transform: scale(2.5);
                                            transition: transform 0.3s ease;
                                        }
                                    </style>
                                    <div class="image-container">
                                        <img src="pics/Studio .jpg" style="width: 100px; height: auto;">
                                    </div>
                                </td>
                                <td>
                                <style>
                                        .copies-input{
                                            width: 40px;
                                            text-align: center;
                                        }
                                    </style>
                                    <form><input type="text" name="copies" value="1" class="copies-input" style="width: 40px;" class="copies-input"></form>
                                </td>
                                <td><form><input type="checkbox" name="sample" value="sample"></form></td>
                            </tr>
                            <tr>
                                <td>Family Portrait</td>
                                <td>Family Portrait - Size (10x12)</td>
                                <td>Rs. 2500</td>
                                <td>
                                    <style>
                                        .image-container:hover img{
                                            transform: scale(2.5);
                                            transition: transform 0.3s ease;
                                        }
                                    </style>
                                    <div class="image-container">
                                        <img src="pics/Family .jpg" style="width: 100px; height: auto;">
                                    </div>
                                </td>
                                <td>
                                <style>
                                        .copies-input{
                                            width: 40px;
                                            text-align: center;
                                        }
                                    </style>
                                    <form><input type="text" name="copies" value="1" class="copies-input" style="width: 40px;" class="copies-input"></form>
                                </td>
                                <td><form><input type="checkbox" name="sample" value="sample"></form></td>
                            </tr>
                            <tr>
                                <td>Batch Photo</td>
                                <td>Batch Photo</td>
                                <td>Rs. 3000</td>
                                <td>
                                    <style>
                                        .image-container:hover img{
                                            transform: scale(2.5);
                                            transition: transform 0.3s ease;
                                        }
                                    </style>
                                    <div class="image-container">
                                        <img src="pics/Batch Photo.jpg" style="width: 100px; height: auto;">
                                    </div>
                                </td>
                                <td>
                                    <style>
                                        .copies-input{
                                            width: 40px;
                                            text-align: center;
                                        }
                                    </style>
                                    <form><input type="text" name="copies" value="1" class="copies-input" style="width: 40px;" class="copies-input"></form>
                                </td>
                                <td><form><input type="checkbox" name="sample" value="sample"></form></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
