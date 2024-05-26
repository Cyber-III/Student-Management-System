<?php 
    // Check if the user is logged in
if(isset($_SESSION['username'])) {
    // Sanitize the username to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    
    // Prepare and execute the SQL query to fetch user's name
    $sql = "SELECT * FROM login_tbl WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    // Check if query executed successfully and user exists
    if($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $student_name = $row['student_name'];
        $course = $row['course'];
        $batch_number = $row['batch_number'];
        $gender = $row['gender'];
        $dob = $row['dob'];
        $nic = $row['nic'];
        $email = $row['email'];
        $contact = $row['contact'];
        $awarding_uni = $row['awarding_uni'];
        $uni_number = $row['uni_number'];
        $lec = $row['lec'];
        
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
  <link rel="shortcut icon" type="dp" href="./pics/graduate.png">

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
        <a href="../../../welcome.php"><img src="../../../pics/L4.png" alt="" class="logos"></a>
        

        <nav class="header-nav ms-auto">

            
            <ul class="d-flex align-items-center">
            
            <a href="../../../welcome.php"><li>Home</li></a>
            <a href="../../../index.php"><li>Logout</li></a>
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
            <h1 class="welcome">Welcome,  <span><?php echo isset($student_name) ? $student_name : ''; ?></span></h1>

        </header>

        <!-- Menu Items -->
        <div class="menu">

            <!-- Profile Category -->
            <div class="item">
                <a class="sub-btn"><i class="far fa-id-card"></i>Student 
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../view_profile/view_profile.php" class="sub-item">View Profile</a>

                </div>
            </div>
            
            <!-- Payment Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-hand-holding-usd"></i>Student Payments
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../PaymentSection/make_payment/make_payment.php" class="sub-item">Make Payments</a>
                    <a href="../../../PaymentSection/view_paymentStatus/view_paymentStatus.php" class="sub-item">View Payments Status</a>
                    <a href="../../../PaymentSection/upload_paymentReceipt/upload_paymentReceipt.php" class="sub-item">Upload Payment Receipts</a>
                </div>
            </div>

            <!-- Library Category -->
            <div class="item">
                <a class="sub-btn"><i class="fa fa-book"></i>Library Books
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../LibrarySection/ReserveBooks/reserveBooks.php" class="sub-item">Reserve Library Books</a>
                    <a href="../../../LibrarySection/ManageReservedBooks/manageReservedBooks.php" class="sub-item">Manage Reserved Books</a>

                </div>
            </div>

            <!-- Graduation Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-user-graduate"></i>Graduation
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../GraduationSection/GraduationSchedule/graduationSchedule.php" class="sub-item">Graduation Schedule</a>
                    <a href="../../../GraduationSection/RegisterGrad/registerGrad.php" class="sub-item">Register for Graduation</a>
                    <a href="../../../GraduationSection/GradPhotos/gradPhotos.php" class="sub-item">Graduation Photos</a>
                    <a href="../../../GraduationSection/RegisterSummary/registerSummary.php" class="sub-item">Registration Summary</a>
                </div>
            </div>
            
            <!-- Membership Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-award"></i>Memberships
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../MembershipSection/library_mem/library_mem.php" class="sub-item">Library Membership</a>
                    <a href="../../../MembershipSection/recreation_mem/recreation_mem.php" class="sub-item">Recreation Membership</a>
                    
                </div>
            </div>

            <!-- Exam Category -->
            <div class="item">
                <a class="sub-btn"><i class="far fa-file-alt"></i>Exams
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="" class="sub-item">Exam Schedule</a>
                    <a href="" class="sub-item">Exam Submissions</a>
                    <a href="" class="sub-item">Exam Admission</a>
                    
                </div>
            </div>

            <!-- Assignments Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-swatchbook"></i>Assignments
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="" class="sub-item">Assignment Schedule</a>
                    <a href="" class="sub-item">Assignment Submissions</a>
                    <a href="" class="sub-item">Assignment Feedback</a>
                    <a href="" class="sub-item">Add Mitigation Request</a>
                    <a href="" class="sub-item">View Mitigation Request</a>
                    
                </div>
            </div>

            <!-- Class Schedule Category -->
            <div class="item">
                <a class="sub-btn"><i class="far fa-calendar-alt"></i>Class Schedule
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../Class Schedule/classSchedule.php" class="sub-item">View Class Details</a>
                    
                </div>
            </div>

            <!-- Results Category -->
            <div class="item">
                <a class="sub-btn"><i class="fa fa-trophy"></i>Results
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="" class="sub-item">Assignment Results</a>
                    <a href="" class="sub-item">Exam Results</a>
                    <a href="" class="sub-item">Final Results</a>
                    
                </div>
            </div>

            <!-- Course Modules -->
            <div class="item">
                <a href="../../../Course Modules/course_module.php"><i class="fas fa-info-circle"></i>Course Modules </a>
            </div>
            
            <!-- Course Materials -->
            <div class="item">
                <a href="../../../Course Materials/courseMaterials.php"><i class="fas fa-book-reader"></i>Course Materials </a>
            </div>

            <!-- Penalty -->
            <div class="item">
                <a href=""><i class="fa fa-dollar"></i>Penalty </a>
            </div>

            <!-- Course Guidlines Category -->
            <div class="item">
                <a class="sub-btn"><i class="far fa-question-circle"></i>Course Guidlines
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="" class="sub-item">Sample Certificate</a>
                    <a href="" class="sub-item">Student Guidlines</a>
                    
                </div>
            </div>

            <!-- Notice Board -->
            <div class="item">
                <a href="../../../Notice-Board-Section/noticeboard.php"><i class="far fa-sticky-note"></i>Notice Board </a>
            </div>

            <!-- Message -->
            <div class="item">
                <a href="../../../MessageSection/message.php"><i class="fas fa-envelope"></i>Message </a>
            </div>


            <!-- Call Center -->
            <div class="item">
                <a href="../../../Call-Section/callcenter.php"><i class="fas fa-phone-alt"></i>Call Center </a>
            </div>

            <!-- Lecture Evaluation -->
            <div class="item">
                <a href=""><i class="fas fa-chart-line"></i>Lecture Evaluation </a>
            </div>

            <!-- Vacancies -->
            <div class="item">
                <a href=""><i class="fas fa-user-plus"></i>Vacancies </a>
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