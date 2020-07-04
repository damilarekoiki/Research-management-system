<?php
    include("../app/init.php");
    if(!isset($_SESSION['email'])){
        $master->redirect("../index.php");
    }

    if(isset($_GET['research_search'])){
        if($_GET['research_search']!=""){
            $research_title=$_GET['research_search'];
            $all_researches=$research->search($research_title);
        }else{
            $master->redirect("index.php");
            exit();
        }
        
    }else{
        $master->redirect("index.php");
        exit();
    }
    
    $all_references_avail=$research->get_all_references_avail();
?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from wrappixel.com/demos/admin-templates/monster-admin/main/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 15 Nov 2017 11:13:51 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <title>RWMS-Admin</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="../assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="../assets/plugins/css-chart/css-chart.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <link href="../app/assets/css/select2.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '../../../../../www.google-analytics.com/analytics.js', 'ga');
        ga('create', 'UA-85622565-1', 'auto');
        ga('send', 'pageview');
    </script>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include("header_loggedin.php");?>
        
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include("sidenav.php");?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <div class="col-md-10 mx-auto mb-5">
                        <?php if(isset($_GET['research_search'])){?>
                            <h4 class="section-heading" style="font-weight:bold">Search result for <?php echo $_GET['research_search'];?></h4><?php } ?> <br>
                            <h6><?php echo count($all_researches);if(count($all_researches)==1)echo " result";else echo " results"?> found</h6>
                        <?php
                  $i=0;
                  if (!empty($all_researches)) {
                  foreach ($all_researches as $researche) {
                    $i++;
                    $title=$researche['research_title'];
                    $description=$researche['research_description'];
                    $research_id=$researche['research_id'];
                    $researcher_id=$researche['researcher_id'];
                    $researcher_pix=$research->get_user_data($researcher_id)['profile_pix'];
                    $researcher_name=$research->get_user_data($researcher_id)['surname']." ".$research->get_user_data($researcher_id)['other_names'];
                    // $researcher_user_role=$research->get_user_data($researcher_id)['user_role'];
                    // $logged_in_user_role=$research->get_user_data($user_id)['user_role'];


                    // $research_files=$research->get_all_research_files($research_id);
                    $research_text="";
                    $text_id="";
                    if(!empty($research->get_research_text($research_id))){
                      $research_text=$research->get_research_text($research_id)['text'];
                      $text_id=$research->get_research_text($research_id)['text_id'];
                    }

                    $contribution_files=$research->get_all_contribution_files($research_id);
                    // $contribution_texts=$research->get_all_contribution_texts($research_id);

                    $total_collaborators=$research->get_total_collaborators($research_id);

                    $researcher_contribution_text="";
                    $research_contribution_text_id=0;
?>
<div style="margin-bottom:110px" class="researchDiv" id="researchDiv<?php echo $i; ?>">
  <div class="row">
    <div class="col-md-1">
      <img src="../<?php echo $researcher_pix;?>" class="" height="18">
      <div style="font-size:10px;"><strong title="<?php echo $researcher_name;?>"><?php if(strlen($researcher_name)>10){echo substr($researcher_name,0,10)."...";}else{ echo $researcher_name; }?></strong></div>
    </div>
    <div class="col-md-10"><h3 class="research-title"> <?php echo ucwords(strtolower($title))?></h3></div>
    <div class="col-md-1">

        <div class="dropdown show">
          <a class="btn" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;"><i class="fa fa-ellipsis-v"> </i></a>
          <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">

            <button class="nav-link js-scroll-trigger btn" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="removeResearch(<?php echo $research_id;?>,'researchDiv<?php echo $i; ?>')">Remove research</button>
            
          </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-10 mx-auto text-center"> <h6 class="research-description"><?php echo ucfirst(strtolower($description))?></h6></div>
  </div>
  <div class="row research-write-up-div" style="margin-top:15px">
    <div class="col-md-1"></div>
    
    <div class="col-md-11">
      <?php
      if(!empty($research_text)){
      ?>
        <div style="border: dotted 1px black;padding:7px;border-radius:5px" class=" well">
          <div class="research-write-up"><?php echo $research_text;?></div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>

  <div class="row compose-write-up-div" style="display:none">
    <div class="col-md-1"></div>
    <div  class="col-md-11">
      <form class="write-up-form" id="writeUpForm<?php echo $i;?>" research-id="<?php echo $research_id;?>" researcher-id="<?php echo $researcher_id;?>">
        <div class='form-group'>
          <textarea class='form-control write-up-field' cols='30' rows='10' name="write_up<?php echo $i;?>" text-id="<?php echo $text_id;?>"><?php echo $research_text;?></textarea>
        </div>
        <div class="form-group">
          <label for="references<?php echo $i;?>">Add references (Optional)</label>
          <select class="form-control references" multiple style="width:100%" id="references<?php echo $i;?>">
          <?php
              foreach ($all_references_avail as $ref_avail) {
                $ref_id=$ref_avail["reference_id"];
                $research_topic=$ref_avail["reference"];
                $sel="";
                if($research->research_text_owns_reference($text_id,$ref_id)){
                  $sel="selected";
                }
                echo "<option datavalue='$ref_id' reference_name='$research_topic' $sel>$research_topic</option>";
              }
          ?>
          </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit write-up" class="btn btn-primary writeUpBtn">
        </div>
      </form>
    </div>
  </div>

  <div class="row" style="margin-top:10px;padding:5px 0px 5px 10px">
    <?php
      $total_contributors=$research->get_total_num_contributors($research_id);
      $total_contributed_files=$research->get_total_num_contribution_files($research_id);
      $total_contributed_texts=$research->get_total_num_contribution_texts($research_id);
      $total_contributions=$total_contributed_files+$total_contributed_texts;

      $total_followers=$research->get_total_num_followers($research_id);

      $all_research_files=$research->get_all_research_files($research_id);
      $total_research_files=count($all_research_files);
      $t_r_f_msg="";
      if($total_research_files>1){
        $t_r_f_msg="$total_research_files files added";
      }elseif($total_research_files==1){
        $t_r_f_msg="$total_research_files files added";
      }
      

      $total_comments=$research->get_total_num_comments($research_id);

      
    ?>
      <div class="col-md-1"></div>
      <div class="col-md-4">
        <span id="t_r_f_msg<?php echo $i;?>" onclick="showResearchFilesModal(<?php echo $research_id;?>,0,0)" data-toggle="modal" data-target="#newModal" style="cursor:pointer;color:blue;border-bottom:1px solid blue;">
          <?php echo $t_r_f_msg;?>
        </span>
      </div>
      <div class="col-md-6">
      </div>
      <div class="col-md-1">
      </div>
      

  </div>
  <div class="row" style='padding:0px 0px 0px 10px'>
    <div class="col-md-1"></div>
    <?php
      if($research->is_shared_to_public($research_id)) echo "<div class='col-md-4'><b style='color:grey'>Shared to public</b></div>";
    ?>
  </div>
  <div class="row">
    <div class="col-md-1" style='padding:0px 0px 0px 10px'></div>
  </div>
  <div class="row" style='padding:5px 0px 0px 10px'>
    <div class="col-md-1"></div>
    <div class='col-md-4'><b style='color:grey'><span class="numb-collab"><?php echo $total_collaborators; ?></span> Collaborators</b></div>
  </div>

  <div class="row" style="margin-top:25px;">
    <div class="col-md-2"></div>

    <div class="col-md-3">
      <i class="fa fa-comment-o addComment" data-toggle="modal" data-target="#myModal" style="cursor:pointer;color:<?php echo $comment_color;?>;" research-id="<?php echo $research_id; ?>" researcher-id="<?php echo $researcher_id; ?>" is-authorized="<?php echo $is_authorized;?>" id="addComment<?php echo $i;?>"> <?php echo $total_comments?></i>
    </div>
    <?php
      $contribution_text_references_html="";
      foreach ($all_references_avail as $ref_avail) {
        $ref_id=$ref_avail["reference_id"];
        $research_topic=$ref_avail["reference"];
        $sel="";
        if($research->research_contribution_text_owns_reference($research_contribution_text_id,$ref_id)){
          $sel="selected";
        }
        $contribution_text_references_html.="<option datavalue='$ref_id' reference_name='$research_topic' $sel>$research_topic</option>";
      }

      
    ?>
    <div class="col-md-4">
      <i class="fa fa-support contribute" data-toggle="modal" data-target="#myModal" style="cursor:pointer;color:<?php echo $contribution_color;?>" research-id="<?php echo $research_id; ?>" researcher-id="<?php echo $researcher_id; ?>" contribution-text-id="<?php echo $research_contribution_text_id; ?>" contribution-text="<?php echo $researcher_contribution_text_content; ?>" contribution_text_references_html="<?php echo $contribution_text_references_html;?>" is-authorized="<?php echo $is_authorized;?>" id="cont<?php echo $i?>"> <?php echo $total_contributions?></i>
    </div>
    <div class="col-md-3">
      <i class="fa fa-blind follow" style="cursor:pointer;color:<?php echo $follow_color;?>" research-id="<?php echo $research_id; ?>" researcher-id="<?php echo $researcher_id; ?>" follower-id="<?php echo $follower_id; ?>" id="follow<?php echo $i;?>"> <?php echo $total_followers?></i>
    </div>
  </div>

</div>

<?php
                  }
                }else{
                  echo "<div><span style='color;grey'>No result found</span></div>";
                }
                ?>
                    <!-- </div> -->
                    </div>
                </div>
                
            </div>







            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content" style="width:100%">
                    <div class="modal-header">
                        <h4 class="modal-title" id="comContTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="modalBody-comments">
                    <a href='#' role='button' id='reshowContCont' onclick="showModalContents('reshowContCont','contributionTextForm')" style="display:none;color:blue;">View all contributions</a>
                        <div id="commentContents">
                        Body Comments            
                        </div>


                        <div id="contributionContents">
                        Body Contributions            
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div id="CommentContributionDiv" class="mr-auto">
                        </div>
                        <div id="notAuthorizedDiv" class="mr-auto"></div>
                    </div>

                    

                    
                    </div>
                </div>
            </div>

            <!-- New Modal -->
            <div id="newModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="newModalTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-auto" id="newModalBody" style="padding:7px">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                  
                    </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                &copy; Group 2B Term Project - RWMS 2018. All Rights Reserved.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <script src="../assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!-- Chart JS -->
    <script src="../assets/plugins/echarts/echarts-all.js"></script>
    <script src="../assets/plugins/toast-master/js/jquery.toast.js"></script>
    <!-- Chart JS -->
    <script src="js/dashboard1.js"></script>
    <script src="js/toastr.js"></script>
    <script src="../app/assets/js/select2.full.min.js"></script>
    <script src="../assets/js/bootbox.min.js"></script>

    <script>
        $.toast({
            heading: 'Welcome to RWMS admin dashboard',
            text: 'View neccessary pages and manage the system',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'info',
            hideAfter: 3000, 
            stack: 6
        });
    </script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <!-- <script src="../assets/plugins/styleswitcher/jQuery.style.switcher.js"></script> -->
</body>

        <script>
      $("#role").select2({
          placeholder:"Select Role"
      })
  </script>

    <script>
        var addCommentId=0;
        $(".addComment").click(function(){
            $("#comContTitle").html("View all comments");
            $("#contributionContents, #contributionFormDiv").hide();
            $("#commentContents, #commentFormDiv").show();

            addCommentId=$(this).attr("id");

            researchId=$(this).attr("research-id")
            researcherId=$(this).attr("researcher-id");
            formData=new FormData();
            formData.append("fetch_comments",1);
            formData.append("research_id",researchId);

            $("#comment_research_id").val(researchId)
            $("#comment_researcher_id").val(researcherId)

            $("#notAuthorizedDiv").hide();

            $.ajax({
                url:"../parser/research_parser.php",
                data:formData,
                type:"post",
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                console.log(data);
                data=JSON.parse(data);
                $("#commentContents").html(data.all_comments_made)
                }
            })
        })

        var contId=0;
        var contResearchId=0;
        var contResearcherId=0;

        $(".contribute").click(function(){
            $("#comContTitle").html("View all contributions");
            researchId=$(this).attr("research-id");
            researcherId=$(this).attr("researcher-id");
            
            contResearchId=researchId;
            contResearcherId=researcherId;

            
            contId=$(this).attr("id");


            // $("#contribution_text").text();
            // CKEDITOR.instances["contribution_text"].setData($(this).attr("contribution-text"))
            $("#contributionTextId").val($(this).attr("contribution-text-id"));
            $("#contributionResearchId").val($(this).attr("research-id"));
            $("#contributionResearcherId").val($(this).attr("researcher-id"));

            // $("#contTextreferences").html($(this).attr("contribution_text_references_html"));

            $("#contributionContents, #contributionFormDiv").show();
            $("#commentContents, #commentFormDiv").hide();

            $("#contributionTextForm, #contributionFileForm, #reshowContCont").hide();
            $("#showContributionInput").prop("checked",false);
            $("#showContributionFile").prop("checked",false);
            $("#contributionTextForm, #contributionFileForm").trigger("reset");

            $("#CommentContributionDiv").hide();
            $("#notAuthorizedDiv").hide();
            
            console.log($(this).attr("contribution_text_references_html"))


            $("#contributionContents").html("");


            formData=new FormData();
            formData.append("fetch_contributors",1);
            formData.append("research_id",researchId);

            $.ajax({
                url:"../parser/research_parser.php",
                data:formData,
                type:"post",
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    data=JSON.parse(data)
                    $("#contributionContents").html(data.contribution_content)
                }
            })
        })
    </script>


    <script>
        function showContributorFilesModal(research_id,contributor,end) {
            formData=new FormData();
            formData.append("get_contributor_files",1);
            formData.append("research_id",research_id);
            formData.append("contributor",contributor);
            formData.append("end",end);

            $("#newModalTitle").html("Contributor's files to research")

            $.ajax({
                url:"../parser/research_parser.php",
                data:formData,
                type:"post",
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    $("#newModalBody").html(data);
                }
            })
        }

        function showResearchFilesModal(research_id,researcher_id,end) {
            formData=new FormData();
            formData.append("get_research_files",1);
            formData.append("research_id",research_id);
            formData.append("researcher_id",researcher_id);
            formData.append("end",end);

            $("#newModalTitle").html("All added files").show()


            $.ajax({
                url:"../parser/research_parser.php",
                data:formData,
                type:"post",
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    $("#newModalBody").html(data);
                }
            })
        }

        function showContributorWriteUpModal(research_id,contributor)  {
            formData=new FormData();
            formData.append("get_contributor_writeup",1);
            formData.append("research_id",research_id);
            formData.append("contributor",contributor);

            $.ajax({
                url:"../parser/research_parser.php",
                data:formData,
                type:"post",
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    $("#newModalBody").html(data);
                    // $("#commentContents").html(data)
                    // data=JSON.parse(data);
                    // $("#loginBtn").val("Login")
                    // if(data.status==1){
                    //     $("#errorMsg").html("<div class='alert alert-success'>"+data.message+"</div>");
                    //     setTimeout(() => {
                    //         window.location=data.url;                        
                    //     }, 3000);
                    // }else{
                    //     $("#errorMsg").html("<div class='alert alert-danger'>"+data.message+"</div>");
                    // }
                }
            })
        }

        function removeResearch(researchId,researchDivId) {
            formData=new FormData();
            formData.append("remove_research",1)
            formData.append("research_id",researchId);

            bootbox.confirm({
                message: "Are you sure you want to delete research",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                if(result){
                    $.ajax({
                    url:"../parser/admin_parser.php",
                    data:formData,
                    type:"post",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data){
                        console.log(data);
                        data=JSON.parse(data);
                        if(data.status==1){
                            $("#"+researchDivId).remove();
                        }
                        alert(data.message);
                    }
                    })
                }
                }
            });
        }
    </script>
</html>
