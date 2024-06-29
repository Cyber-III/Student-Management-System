<?php 
    // Check if the user is logged in
if(isset($_SESSION['username'])) {
    // Sanitize the username to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    
    // Prepare and execute the SQL query to fetch user's name
    $sql = "SELECT * FROM lecturers WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
  // Check if query executed successfully and user exists
  if($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $gender = $row['gender'];
    $password = $row['password'];
    $name = $row['name'];
    $email = $row['email'];
    $department = $row['department'];
    $dob = $row['dob'];
    $nic = $row['nic'];
    $contact = $row['contact'];

        
        
        
    } else {
        // Redirect to login page if user does not exist
        header("Location: login.php");
        exit();
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();

}



?>

<!doctype html>
<html lang="en">

<head>
  <title>ABC INSTITUTE</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Upper Icon-->
    <link rel="icon" type="image/png" href="../../../pics/graduate.png">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style-template.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
        
        <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <a href="../../../Lecturer/welcome.php"><img src="../../../pics/L4.png" alt="" class="logos"></a>
        

        <nav class="header-nav ms-auto">

            
            <ul class="d-flex align-items-center">
            
            <a href="../../../Lecturer/welcome.php"><li>Home</li></a>
            <a href="../../../Lecturer/index.php"><li>Logout</li></a>
            <div class="animation start-home"></div>
            </ul>
        </nav>

    </header><!-- End Header -->

    <!-- =========================================================== left-side bar begin =========================================================== -->

    <div class="sidebar">
        <!-- header section -->
        <header>
            <!-- close button -->
            <!-- <div class="close-btn">
                <i class="fas fa-times"></i>
            </div> -->

            <!-- welcome -->
            <h1 class="welcome">Welcome,  <span><?php echo isset($name) ? $name : ''; ?></span></h1>

        </header>

        <!-- Menu Items -->
        <div class="menu">

            <!-- Profile Category -->
            <div class="item">
                    <a href="../../../Lecturer/view_profile/view_profile.php" class="sub-item"><i class="far fa-id-card"></i>Profile</a>
            </div>

            <!-- Student Search -->
            <div class="item">
                <a href="../../../Lecturer/studentSearch/studentSearch.php"><i class="fas fa-search"></i>Student Search</a>
            </div>

            <!-- Class Schedule Category -->
            <div class="item">
                    <a href="../../../Lecturer/Class Schedule/classSchedule.php" class="sub-item"><i class="far fa-calendar-alt"></i>Class Schedule</a>
            </div>
            
            <!-- Exam Category -->
            <div class="item">
                <a class="sub-btn"><i class="far fa-file-alt"></i>Exams
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../Lecturer/ExamSection/ExamSchedule/exam_schedule.php" class="sub-item">Exam Schedule</a>

                </div>
            </div>

            <!-- Assignments Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-swatchbook"></i>Assignments
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                  <a href="../../../Lecturer/AssignmentSection/assignmentSchedule/assignment_schedule.php" class="sub-item">Assignment Schedule</a>
                  <!-- <a href="../../../Lecturer/AssignmentSection/assignmentSubmission/assignment_submission.php" class="sub-item">Submissions Management</a> -->
                  <a href="../../../Lecturer/AssignmentSection/mitigation requests/mitigation.php" class="sub-item">Mitigation Requests</a>
              </div>
            </div>

            <!-- Viva -->
            <div class="item">
                <a class="sub-btn"><i class='far fa-sticky-note'></i>Viva
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../Lecturer/viva/viva.php" class="sub-item">Viva Schedule</a>
                    <a href="../../../Lecturer/viva/team.php" class="sub-item">Viva Team Management</a>
                </div>
            </div>

            <!-- Module Category -->
            <div class="item">
            <a href="../../../Lecturer/Course modules/modules.php" class="sub-item"><i class="fa-solid fa-book-open"></i>Course Modules </a>
            </div>
            
            <!-- Course Materials -->
            <div class="item">
              <a href="../../../Lecturer/course Materials/coursematerials.php"><i class="fas fa-book-reader"></i>Course Materials </a>
            </div>

            <!-- Message -->
            <!-- <div class="item">
                <a href="../../../MessageSection/message.php"><i class="fas fa-envelope"></i>Message </a>
            </div> -->

            
            <!-- Lecture Evaluation -->
            <div class="item">
                <a href="../../../Lecturer/EvaluationSection/evaluation.php"><i class="fas fa-chart-line"></i>Lecture Evaluation </a>
            </div>

            <!-- Vacancies -->
            <div class="item">
                <a href="../../../Lecturer/VacancySection/vacancy.php"><i class="fas fa-user-plus"></i>Vacancies </a>
            </div>


        </div>
    </div>

    <!-- =========================================================== left-side bar finish =========================================================== -->

</body>

<!-- Jquery CDN link -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
        $(document).ready(function(){
            var timeout;
            //Jquery for sub-menu
            $('.sub-btn').hover(function(){
                clearTimeout(timeout); // Clear any existing timeout
                $(this).next('.sub-menu').slideDown();
                $(this).find('.dropdown').addClass('rotate');
            }, function(){
                var $submenu = $(this).next('.sub-menu');
                timeout = setTimeout(function(){
                    $submenu.slideUp();
                }, 300); // Adjust delay time as needed
                $(this).find('.dropdown').removeClass('rotate');
            });
    
            // Keep submenu open when hovering over it
            $('.sub-menu').hover(function(){
                clearTimeout(timeout); // Clear any existing timeout
            }, function(){
                $(this).slideUp();
                $('.sub-btn').find('.dropdown').removeClass('rotate');
            });
    
            // Close all submenus when mouse pointer moves outside
            $(document).on('mouseover', function(e){
                if (!$(e.target).closest('.sidebar').length) {
                    $('.sub-menu').slideUp();
                    $('.sub-btn').find('.dropdown').removeClass('rotate');
                }
            });
        });
</script>

<script>
    (function() {
        "use strict";

        /**
         * Easy selector helper function
         */
        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
            return [...document.querySelectorAll(el)]
            } else {
            return document.querySelector(el)
            }
        }

        /**
         * Easy event listener function
         */
        const on = (type, el, listener, all = false) => {
            if (all) {
            select(el, all).forEach(e => e.addEventListener(type, listener))
            } else {
            select(el, all).addEventListener(type, listener)
            }
        }

        /**
         * Easy on scroll event listener 
         */
        const onscroll = (el, listener) => {
            el.addEventListener('scroll', listener)
        }

        /**
         * Sidebar toggle
         */
        if (select('.toggle-sidebar-btn')) {
            on('click', '.toggle-sidebar-btn', function(e) {
            select('body').classList.toggle('toggle-sidebar')
            })
        }

    })();
</script>


</html>