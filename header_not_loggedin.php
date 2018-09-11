<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="<?php echo $home;?>" style="color:black;"><img src="app/assets/logo.png"/></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto"  style="color:black;">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="<?php echo $home;?>" style="color:black;">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="aboutus.php" style="color:black;">About Us</a>
            </li>
            <li class="nav-item">
                <div class="dropdown show">
                    <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;font-size:11px">Login</a>
                    <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
                        <a class="nav-link js-scroll-trigger" href="#">As admin</a>
                        <a class="nav-link js-scroll-trigger" href="user/login.php">As User</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="user/register.php" style="color:black;">Register</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>