<?php
  $total_new_follow_requests=$research->get_total_new_follow_requests($user_id);
  $new_follow_notifications="";
  $notification_color="black";
  if($total_new_follow_requests>0){
    if($total_new_follow_requests>1){
      $new_follow_notifications="You have $total_new_follow_requests new follow requests";
    }else{
      $new_follow_notifications="You have $total_new_follow_requests new follow request";
    }
    $notification_color="red";
  }


  $all_new_researches=$research->get_all_new_researches();
  $total_new_researches=count($all_new_researches);
  $new_research_notifications="";
  $tnr=0;
  // $navbar_background="";
  if($master->get_user_data($user_id)['user_role']==1){
    // $navbar_background="#DAA520";
    $tnr=$total_new_researches;
    if($total_new_researches>0){
      if($total_new_researches>1){
        $new_research_notifications="$total_new_researches new researches were created";
      }else{
        $new_research_notifications="$total_new_researches new research was created";
      }
      $notification_color="red";
    }
  }
  $total_notification=$total_new_follow_requests+$tnr;
  $total_notification_msg="";
  if($total_notification>0){
    $total_notification_msg="<sup>$total_notification</sup>";
  }


  // $total_new_follow_requests=$research->get_total_new_follow_requests($researcher_id);
  // $new_follow_notifications="";
  // if($total_new_follow_requests>0){
  //   $new_follow_notifications="You have $total_new_follow_requests new follow requests";
  // }
?>

<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="../<?php echo $home;?>" style="color:black;"><img src="../app/assets/logo.png"/></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li>
              <form action="search_result.php" method="get">
                <div class="form-inline">
                    <div class="form-group">
                      <input type="text" name="research_search" id="" class="form-control" placeholder="Enter a research title">                    
                    </div>
                    <div class="form-group">
                      <button class="btn btn-primary"><i class="fa fa-search"></i></button>                   
                    </div>
                </div>
              </form>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="../<?php echo $home;?>" style="color:black;">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="aboutus.php" style="color:black;">About Us</a>
            </li>
            <li class="nav-item">
                <span class="dropdown show">
                    <a class="btn" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:<?php echo $notification_color;?>"><i class="fa fa-bell" style="color:<?php echo $notification_color;?>"></i><?php echo $total_notification_msg;?></a>
                  <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
                    <?php
                    if($total_new_follow_requests>0){
                      ?>
                      <a class="nav-link js-scroll-trigger" href="view_all_follow_requests.php" style="font-size:10px;font-weight:bold;color:blue;text-decoration:underline"><?php echo $new_follow_notifications;?></a>
                      <?php
                    }else{
                      echo '<a class="nav-link js-scroll-trigger" href="view_all_follow_requests.php" style="font-size:10px;font-weight:bold;color:blue;text-decoration:underline">No new follow request, View older</a>';
                    }
                    if($master->get_user_data($user_id)['user_role']==1){
                      if($total_new_researches>0){
                    ?>
                        <a class="nav-link js-scroll-trigger" href="view_all_researches.php" style="font-size:10px;font-weight:bold;color:blue;text-decoration:underline"><?php echo $new_research_notifications;?></a>
                    <?php
                      }else{
                        echo '<a class="nav-link js-scroll-trigger" href="view_all_researches.php" style="font-size:10px;font-weight:bold;color:blue;text-decoration:underline">No new created research, View older</a>';
                      }
                    }
                      ?>
                  </div>
                </span>
            </li>
            <li class="nav-item">
              <span class="dropdown show">
                  <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;font-size:11px"><img src="../<?php echo $user_img;?>" class="" height="18"> <br>
              <span style="font-size:9px;"><?php echo $user_surname;?></span></a>
                  <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
                      <a class="nav-link js-scroll-trigger" href="#">View Profile</a>
                      <a class="nav-link js-scroll-trigger" href="my_researches.php">My researches</a>
                      <a class="nav-link js-scroll-trigger" href="view_all_follow_requests.php">All follow requests</a>
                      <?php if($master->get_user_data($user_id)['user_role']==1){
                      ?>
                        <a class="nav-link js-scroll-trigger" href="view_all_researches.php">All researches</a>
                      <?php
                      }?>
                      <a class="nav-link js-scroll-trigger" href="../logout.php?logout=1">Logout</a>
                  </div>
              </span>
            </li>
            
          </ul>
        </div>
      </div>
    </nav>




    