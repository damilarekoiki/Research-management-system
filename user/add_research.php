<?php
    include ("../app/init.php");
    if(!isset($_SESSION['email'])){
        $master->redirect("../index.php");
    }
    $all_collaborators=$research->get_all_collaborators();
    $all_references_avail=$research->get_all_references_avail();
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
    <link href="../app/assets/css/select2.min.css" rel="stylesheet">

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
      <div class="row"></div>
        <div id="errorMsg" class="col-md-8 mx-auto"></div>
        <div class="row">
            
            <div class="col-md-8 mx-auto mb-5">
                <h4 class="section-heading">Add Research</h4> <br>
                <form id="addResearchForm">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Title"/>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" name="description" class="form-control"  id="description" placeholder="Description"/>
                    </div>
                    <div class="form-group">
                        <label for="collaborators">Add collaborators (Optional)</label>
                        <select name="collaborators[]" class="form-control" id="collaborators" multiple>
                            <?php
                                foreach ($all_collaborators as $colb) {
                                    $colb_id=$colb['user_id'];
                                    $colb_name=$colb['surname']." ".$colb['other_names'];
                                    echo "<option value='$colb_id'>".$colb_name."</option>";
                                }
                            ?>
                            <!-- <option value="ndndn">fgg</option>
                            <option value="ndndn">fgg</option>
                            <option value="ndndn">fgg</option> -->
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add Reasearch" class="btn btn-primary" id="addResearchBtn"/>
                    </div>
                </form>
                <!-- </div> -->
            </div>
        </div>
      </div>
    </section>

    

    <footer>
      <div class="container">
        <p>&copy; Your Website 2018. All Rights Reserved.</p>
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
    <script src="../app/assets/js/select2.full.min.js"></script>

    <script>
        $("#collaborators").select2({
            placeholder:"Add Collaborators"
        });
        // $("input.select2-search__field[placeholder='Add Collaborators']").on('keypress',function(e){
        //     console.log("keyup")
        //     sample_name=$(this).val();
            
        //     var formData = new FormData();
        //     formData.append("fetch_collaborators","1");
        //     formData.append("sample_name",sample_name);
            
        //     $.ajax({
        //         url:"../parser/research_parser.php",
        //         data:formData,
        //         type:"post",
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         // beforeSend:function(){
        //         //     $("#collaborators").html("");
        //         // },
        //         success:function(data){
        //             $("#collaborators").html("");
        //             console.log(sample_name);
                    
        //             console.log(data);
        //             data=JSON.parse(data);
        //             for(i=0;i<data.length;i++){
        //                 collaboratorId=data[i].user_id;
        //                 collaboratorName=data[i].surname+" "+data[i].other_names;
        //                 $("#collaborators").append("<option value='"+collaboratorId+"'>"+collaboratorName+"</option>");
        //             }
                    
        //         }
        //     })
        // });
        
    </script>

    <script>
        $("#addResearchForm").submit(function(e){
            e.preventDefault();
            $("#addResearchBtn").val("Please wait...");

            formData=new FormData($("#addResearchForm")[0]);
            formData.append("add_research",1);

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
                    $("#addResearchBtn").val("Register");
                    if(data.status==1){
                        $("#errorMsg").html("<div class='alert alert-success'>"+data.message+"</div>");
                        setTimeout(() => {
                            window.location=data.url;                        
                        }, 3000);
                    }else{
                        $("#errorMsg").html("<div class='alert alert-danger'>"+data.message+"</div>");
                    }
                    // alert(data.message)
                }
            })
        })
    </script>

  </body>

</html>
