<!DOCTYPE html>
<html lang="en">

<head>
  <!-- new commit -->
<style>
    .social-icons-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
    }

    .social-icons-container a {
        margin-right: 50px;
    }

</style>

  <title>Login</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!--Upper Icon-->
  <link rel="shortcut icon" type="dp" href="./pics/graduate.png">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>


  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Student Information System</h1>
        <div class="col-md-8 col-lg-7 col-xl-6">
          <img src="pics/login.png" class="img-fluid" alt="Phone image" height="300px" width="600px">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <form action="login.php" method="post">
            
            <!-- <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Login </p> -->

            <!-- Email input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form1Example13"> <i class="bi bi-person-circle"></i> Username</label>
              <input type="text" id="form1Example13" class="form-control form-control-lg py-3" name="username" autocomplete="off" placeholder="enter your username" style="border-radius:25px ;" />

            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form1Example23"><i class="bi bi-chat-left-dots-fill"></i> Password</label>
              <input type="password" id="form1Example23" class="form-control form-control-lg py-3" name="password" autocomplete="off" placeholder="enter your password" style="border-radius:25px ;" />

            </div>


            <!-- Signin button -->
            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
              <input type="submit" value="Sign in" name="login" class="btn btn-warning btn-lg text-light my-2 py-3" style="width:100% ; border-radius: 30px; font-weight:600; background-color: #3333ff; border-color: #3333ff; color: #FFFFFF;" />
          
            </div>

            <!-- Social Icons -->
            <div class="social-icons-container">
              <a href="https://www.instagram.com/icbtsrilanka/" target="_blank"> <img src="icons/instagram.png" alt="" height="32px" width="32px"> </a>
              <a href="https://www.facebook.com/ICBTsrilanka/" target="_blank"> <img src="icons/facebook.png" alt="" height="32px" width="32px"> </a>
              <a href="https://www.youtube.com/channel/UC3bDumjXNsq2ixYkUT7wT_g" target="_blank"> <img src="icons/youtube.png" alt="" height="32px" width="32px"> </a>
              <a href="https://www.linkedin.com/school/icbtcampus/" target="_blank"> <img src="icons/linkedin.png" alt="" height="32px" width="32px"> </a>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </section>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>
