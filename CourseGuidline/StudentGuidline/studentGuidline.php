<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style-template.css">
    <link rel="stylesheet" href="style-studentGuidline.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Student Guidelines</title>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <!-- Heading -->
                <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-4">University Student Guidelines</h1>
                
                <!-- List of guidelines -->
                <ul class="list-group">
                    <!-- Each guideline is represented as a list item -->
                    <li class="list-group-item">Attend all classes regularly and be punctual.</li>
                    <li class="list-group-item">Complete and submit all assignments on time.</li>
                    <li class="list-group-item">Participate actively in class discussions and activities.</li>
                    <li class="list-group-item">Maintain academic integrity by avoiding plagiarism and cheating.</li>
                    <li class="list-group-item">Respect the diversity of opinions and cultures among your peers.</li>
                    <li class="list-group-item">Utilize university resources such as libraries, labs, and counseling services.</li>
                    <li class="list-group-item">Follow the university's code of conduct and policies.</li>
                    <li class="list-group-item">Communicate regularly with your instructors and advisors.</li>
                    <li class="list-group-item">Engage in extracurricular activities and join student organizations.</li>
                    <li class="list-group-item">Take care of your mental and physical health.</li>
                    <li class="list-group-item">Be proactive in seeking help if you face academic or personal challenges.</li>
                    <li class="list-group-item">Respect university property and facilities.</li>
                    <li class="list-group-item">Stay informed about university announcements and updates.</li>
                    <li class="list-group-item">Maintain a clean and organized living and study space.</li>
                    <li class="list-group-item">Build and maintain a professional online presence.</li>
                    <li class="list-group-item">Develop good time management skills.</li>
                    <li class="list-group-item">Create a study schedule and stick to it.</li>
                    <li class="list-group-item">Work on improving your communication skills.</li>
                    <li class="list-group-item">Network with peers, faculty, and professionals in your field.</li>
                    <li class="list-group-item">Get involved in community service and volunteer work.</li>
                    <li class="list-group-item">Take advantage of internship and job placement services.</li>
                    <li class="list-group-item">Be aware of and respect deadlines for all academic and administrative tasks.</li>
                    <li class="list-group-item">Keep track of your academic progress and grades.</li>
                    <li class="list-group-item">Stay healthy by eating well and exercising regularly.</li>
                    <li class="list-group-item">Ensure you have a good work-life balance.</li>
                    <li class="list-group-item">Be honest and ethical in all your dealings.</li>
                    <li class="list-group-item">Use social media responsibly.</li>
                    <li class="list-group-item">Seek feedback and act on it to improve yourself.</li>
                    <li class="list-group-item">Be prepared for each class by doing the required readings and assignments.</li>
                    <li class="list-group-item">Make the most of your university experience by exploring new interests and activities.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
