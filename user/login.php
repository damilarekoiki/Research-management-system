<?php
    include ("../app/init.php");
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

    

    <section class="download">
      <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto mb-5">
                <h4 class="section-heading">Login</h4> <br>

                <div id="errorMsg"></div>
                <form id="loginForm">
                    <div class="form-group">
                        <label for="email">Enter email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <label for="email">Enter password</label>
                        <input type="password" name="password" class="form-control"  id="password" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn btn-primary" id="loginBtn"/>
                    </div>
                </form>
                <!-- </div> -->
            </div>
        </div>
      </div>
    </section>

    

        <?php include ("footer.php");?>


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/new-age.min.js"></script>

  </body>
  <script>
      $("#loginForm").submit(function(e){
        e.preventDefault();
        $("#loginBtn").val("Please wait...")
        formData=new FormData($("#loginForm")[0]);
        formData.append("login",1)

        $.ajax({
            url:"../parser/user_parser.php",
            data:formData,
            type:"post",
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                console.log(data);
                data=JSON.parse(data);
                $("#loginBtn").val("Login")
                if(data.status==1){
                    $("#errorMsg").html("<div class='alert alert-success'>"+data.message+"</div>");
                    setTimeout(() => {
                        window.location=data.url;                        
                    }, 3000);
                }else{
                    $("#errorMsg").html("<div class='alert alert-danger'>"+data.message+"</div>");
                }
            }
        })
      })
  </script>
</html>
