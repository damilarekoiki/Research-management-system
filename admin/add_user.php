<?php
    include("../app/init.php");
    $all_users=$coordinator->fetch_all();
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
                        <h4 class="section-heading" style="font-weight:bold;">Add New User</h4> <br>
                        <div id="errorMsg"></div>
                <form id="regForm">
                    <div class="form-group">
                        <label for="sn">Surname</label>
                        <input type="text" name="surname" class="form-control" id="sn" placeholder="Surname"/>
                    </div>
                    <div class="form-group">
                        <label for="other_names">Other Names</label>
                        <input type="text" name="other_names" class="form-control"  id="other_names" placeholder="Other Names"/>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <label for="role">Select role</label>
                        <select name="user_role" id="role" class="form-control">
                            <option></option>
                            <option value="0">Researcher</option>
                            <option value="1">Research Coordinator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add User" class="btn btn-primary" id="regButton"/>
                    </div>
                </form>
                    <!-- </div> -->
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
      $("#regForm").submit(function(e){
          e.preventDefault();
          $("#regButton").val("Please wait...");

          formData=new FormData($("#regForm")[0]);
          formData.append("add_user",1)

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
                $("#regButton").val("Add User");
                if(data.status==1){
                    $("#errorMsg").html("<div class='alert alert-success'>"+data.message+"</div>");
                    setTimeout(() => {
                        location.reload();                        
                    }, 3000);
                }else{
                    $("#errorMsg").html("<div class='alert alert-danger'>"+data.message+"</div>");
                }
            }
          })
      })
    </script>
</html>
