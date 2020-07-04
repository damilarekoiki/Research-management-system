<?php
  include ("app/init.php");
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Research Engine</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/simple-line-icons/css/simple-line-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="device-mockups/device-mockups.min.css">

    <!-- Custom styles for this template -->
    <link href="css/new-age.min.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <?php
        if(!isset($_SESSION['email'])){
            include("header_not_loggedin.php");
        }else{
          include("header_loggedin.php");
        }
    ?>

    <header class="masthead">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-7 my-auto">
            <div class="header-content mx-auto">
              <h1 class="mb-5" style="color:black;">RWMS is an app that manages and documents past and present research projects being carried out in the department</h1>
              <a href="#" class="btn btn-outline btn-xl js-scroll-trigger btn-primary">Admin Login</a>
              <a href="user/login.php" class="btn btn-outline btn-xl js-scroll-trigger btn-primary">User Login</a>
            </div>
          </div>
          <div class="col-lg-5 my-auto">
            <img src="app/assets/desktop.png" class="img-fluid" alt="jhusddiudsiu">

            <!-- <div class="device-container"> -->
              <!-- <div class="device-mockup iphone6_plus portrait white"> -->
                <!-- <div class="device"> -->
                  
                  <div class="button">
                    <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                  </div>
                <!-- </div> -->
              <!-- </div> -->
            <!-- </div>  -->
          </div>
        </div>
      </div>
    </header>

    

    <footer>
      <div class="container">
      <p>&copy; Group 2B Term Project - RWMS 2018. All Rights Reserved.</p>
      <ul class="list-inline">
          <li class="list-inline-item">
          <a href="#">Privacy</a>
          </li>
          <li class="list-inline-item">
          <a href="#">Terms</a>
          </li>
          <li class="list-inline-item">
          <a href="#">FAQ</a>
          </li>
      </ul>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/new-age.min.js"></script>

  </body>

</html>
