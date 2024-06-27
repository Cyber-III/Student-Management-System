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

$activePage = basename($_SERVER['PHP_SELF'], ".php");
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
                <a href="../../../view_profile/view_profile.php" class="sub-item <?php echo $activePage == 'view_profile' ? 'active' : ''; ?>"><i class="far fa-id-card"></i>Student Profile</a>
            </div>
            
            <!-- Payment Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-hand-holding-usd"></i>Student Payments
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../PaymentSection/make_payment/make_payment.php" class="sub-item <?php echo $activePage == 'make_payment' ? 'active' : ''; ?>">Make Payments</a>
                    <a href="../../../PaymentSection/view_paymentStatus/view_paymentStatus.php" class="sub-item <?php echo $activePage == 'view_paymentStatus' ? 'active' : ''; ?>">View Payments Status</a>
                    <a href="../../../PaymentSection/upload_paymentReceipt/upload_paymentReceipt.php" class="sub-item <?php echo $activePage == 'upload_paymentReceipt' ? 'active' : ''; ?>">Upload Payment Receipts</a>
                </div>
            </div>
            
            <!-- Class Schedule Category -->
            <div class="item">
                    <a href="../../../Class Schedule/classSchedule.php" class="sub-item <?php echo $activePage == 'classSchedule' ? 'active' : ''; ?>"><i class="far fa-calendar-alt"></i>Class Schedule</a>
            </div>
            
            <!-- Exam Category -->
            <div class="item">
                <a class="sub-btn"><i class="far fa-file-alt"></i>Exams
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../ExamSection/ExamSchedule/exam_schedule.php" class="sub-item <?php echo $activePage == 'exam_schedule' ? 'active' : ''; ?>">Exam Schedule</a>
                    <a href="../../../ExamSection/ExamSubmission/exam_submission.php" class="sub-item <?php echo $activePage == 'exam_submission' ? 'active' : ''; ?>">Exam Submissions</a>
                    <a href="../../../ExamSection/ExamAdmission/examAdmission.php" class="sub-item <?php echo $activePage == 'examAdmission' ? 'active' : ''; ?>">Exam Admission</a>
                    
                </div>
            </div>

            <!-- Assignments Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-swatchbook"></i>Assignments
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../AssignmentSection\schedule\schedule.php" class="sub-item <?php echo $activePage == 'schedule' ? 'active' : ''; ?>">Assignment Schedule</a>
                    <a href="../../../AssignmentSection\submission\upload_submission.php" class="sub-item <?php echo $activePage == 'upload_submission' ? 'active' : ''; ?>">Assignment Submissions</a>
                    <a href="../../../AssignmentSection\Feedback\feedback.php" class="sub-item <?php echo $activePage == 'feedback' ? 'active' : ''; ?>">Assignment Feedback</a>
                    <a href="../../../AssignmentSection\Mitigation Request\mitigation.php" class="sub-item <?php echo $activePage == 'mitigation' ? 'active' : ''; ?>">Add Mitigation Request</a>
                    <a href="../../../AssignmentSection\Mitigation Request\view_mitigation.php" class="sub-item <?php echo $activePage == 'view_mitigation' ? 'active' : ''; ?>">View Mitigation Request</a>
                    
                </div>
            </div>

            <!-- Viva Session -->
            <div class="item">
                <a href="../../../viva/viva_submission.php" class="sub-item <?php echo $activePage == 'viva_submission' ? 'active' : ''; ?>"> <i class="far fa-sticky-note"></i>Viva Application</a>
            </div>

            <!-- Results Category -->
            <div class="item">
                <a class="sub-btn"><i class="fa fa-trophy"></i>Results
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../ResultSection/Assignment/result.php" class="sub-item <?php echo $activePage == 'result' ? 'active' : ''; ?>">Assignment Results</a>
                    <a href="../../../ResultSection/Exam/Examresult.php" class="sub-item <?php echo $activePage == 'Examresult' ? 'active' : ''; ?>">Exam Results</a>
                    <a href="../../../ResultSection/Final/final.php" class="sub-item <?php echo $activePage == 'final' ? 'active' : ''; ?>">Final Results</a>
                    
                </div>
            </div>

            <!-- Notice Board -->
            <div class="item">
                <a href="../../../Notice-Board-Section/noticeboard.php" class="sub-item <?php echo $activePage == 'noticeboard' ? 'active' : ''; ?>"> <i class="far fa-sticky-note"></i>Notice Board</a>
            </div>

            <!-- Library Category -->
            <div class="item">
                <a class="sub-btn"><i class="fa fa-book"></i>Library Books
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../LibrarySection/ReserveBooks/reserveBooks.php" class="sub-item <?php echo $activePage == 'reserveBooks' ? 'active' : ''; ?>">Reserve Library Books</a>
                    <a href="../../../LibrarySection/ManageReservedBooks/manageReservedBooks.php" class="sub-item <?php echo $activePage == 'manageReservedBooks' ? 'active' : ''; ?>">Manage Reserved Books</a>

                </div>
            </div>

            <!-- Graduation Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-user-graduate"></i>Graduation
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../GraduationSection/GraduationSchedule/graduationSchedule.php" class="sub-item <?php echo $activePage == 'graduationSchedule' ? 'active' : ''; ?>">Graduation Schedule</a>
                    <!-- <a href="../../../GraduationSection/RegisterGrad/registerGrad.php" class="sub-item <?php echo $activePage == 'registerGrad' ? 'active' : ''; ?>">Register for Graduation</a> -->
                    <a href="../../../GraduationSection/GradPhotos/gradPhotos.php" class="sub-item <?php echo $activePage == 'gradPhotos' ? 'active' : ''; ?>">Graduation Photos</a>
                    <!-- <a href="../../../GraduationSection/RegisterSummary/registerSummary.php" class="sub-item <?php echo $activePage == 'registerSummary' ? 'active' : ''; ?>">Registration Summary</a> -->
                </div>
            </div>
            
            <!-- Membership Category -->
            <div class="item">
                <a class="sub-btn"><i class="fas fa-award"></i>Memberships
                    <!-- Dropdown -->
                </a>
                <div class="sub-menu">
                    <a href="../../../MembershipSection/library_mem/library_mem.php" class="sub-item <?php echo $activePage == 'library_mem' ? 'active' : ''; ?>">Library Membership</a>
                    <a href="../../../MembershipSection/recreation_mem/recreation_mem.php" class="sub-item <?php echo $activePage == 'recreation_mem' ? 'active' : ''; ?>">Recreation Membership</a>
                    
                </div>
            </div>

            <!-- Course Modules -->
            <div class="item">
                <a href="../../../Course Modules/course_module.php" class="sub-item <?php echo $activePage == 'course_module' ? 'active' : ''; ?>"><i class="fas fa-info-circle"></i>Course Modules </a>
            </div>
            
            <!-- Course Materials -->
            <div class="item">
                <a href="../../../Course Materials/courseMaterials.php" class="sub-item <?php echo $activePage == 'courseMaterials' ? 'active' : ''; ?>"><i class="fas fa-book-reader"></i>Course Materials </a>
            </div>

            <!-- Course Guidlines Category -->
            <div class="item">
                    <a href="../../../CourseGuidline/StudentGuidline/studentGuidline.php" class="sub-item <?php echo $activePage == 'studentGuidline' ? 'active' : ''; ?>"><i class="far fa-question-circle"></i>Course Guidlines</a>
            </div>           

            <!-- Message -->
            <div class="item">
                <a href="../../../MessageSection/message.php" class="sub-item <?php echo $activePage == 'message' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i>Message </a>
            </div>

            <!-- Call Center -->
            <div class="item">
                <a href="../../../Call-Section/callcenter.php" class="sub-item <?php echo $activePage == 'callcenter' ? 'active' : ''; ?>"><i class="fas fa-phone-alt"></i>Call Center </a>
            </div>

            <!-- Lecture Evaluation -->
            <div class="item">
                <a href="../../../EvaluationSection/evaluation.php" class="sub-item <?php echo $activePage == 'evaluation' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i>Lecture Evaluation </a>
            </div>

            <!-- Vacancies -->
            <div class="item">
                <a href="../../../VacancySection/vacancy.php" class="sub-item <?php echo $activePage == 'vacancy' ? 'active' : ''; ?>"><i class="fas fa-user-plus"></i>Vacancies </a>
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

        // JavaScript for sidebar toggle
        let subMenu = document.getElementsByClassName('sub-btn');
    for (let i = 0; i < subMenu.length; i++) {
      subMenu[i].addEventListener('click', function() {
        this.classList.toggle('active');
        this.nextElementSibling.classList.toggle('show');
      });
    }
</script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
integrity="sha384-oBqDVmMz4fnFO9gybVp+6eP23Q8uMGFcm5iD9GNGIa0WlH/eUcZOw7KHEd0P2vHg"
crossorigin="anonymous"></script>


</html>