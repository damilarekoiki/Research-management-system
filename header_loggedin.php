<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav" style="border-bottom:1px solid black">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="<?php echo $home;?>" style="color:black;"><img src="app/assets/logo.png"/></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="<?php echo $home;?>" style="color:black;">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="aboutus.php" style="color:black;">About Us</a>
            </li>
            <li class="nav-item">
              <img src="<?php echo $user_img;?>" class="" height="18"> <br>
              <span style="font-size:9px;"><?php echo $user_surname;?></span>
                <span class="dropdown show">
                    <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;font-size:11px"></a>
                    <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
                        <a class="nav-link js-scroll-trigger" href="#">View Profile</a>
                        <a class="nav-link js-scroll-trigger" href="logout.php?logout=1">Logout</a>
                    </div>
                </span>
            </li>

          </ul>
        </div>
      </div>
    </nav>