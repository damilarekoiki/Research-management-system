<?php
    include ("../app/init.php");
    if(!isset($_SESSION['email'])){
        $master->redirect("../index.php");
    }

    $all_researches=$research->get_all_researches();
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
            
            <div class="col-md-10 mx-auto mb-5">
                <h4 class="section-heading" style="font-weight:bold;">All Created Researches By All Users</h4>
                <h6>Only the ones you approve will appear in the home page</h6>
                <?php
                    $i=0;
                    if(!empty($all_researches)){
                        foreach ($all_researches as $researche) {
                            $i++;
                            $researcher_id=$researche['researcher_id'];
                            $research_id=$researche['research_id'];
                            $is_approved=$researche['is_approved'];
                            $is_seen=$researche['is_seen'];

                            $research_details=$research->details($research_id);
                            // var_dump($research_details);
                            $research_title=$research_details['research_title'];

                            $approval_background="";
                            $approval_border="";
                            if($is_seen==0){
                                $approval_background="#E6E6FA";
                            }else{
                                $approval_border="1px solid black";
                            }

                            $researcher_details=$master->get_user_data($researcher_id);
                            $researcher_name=$researcher_details['surname']." ".$researcher_details['other_names'];
                            $researcher_pix=$researcher_details['profile_pix'];


                            $approval_msg="";

                            if($is_approved==1){
                                $approval_msg="
                                    <div class='col-md-4'>
                                        <span style='font-size:10px;color:blue'>Approved</span>
                                    </div>
                                    <div class='col-md-4'>
                                        <button class='btn btn-primary'". '  onclick="decideOnResearchApproval(2,'.$research_id.','.$researcher_id.','."'approvalDiv$i'".')"'.">Make Pending</button>
                                    </div>
                                    <div class='col-md-4'>
                                        <button class='btn btn-danger'". '  onclick="decideOnResearchApproval(0,'.$research_id.','.$researcher_id.','."'approvalDiv$i'".')"'.">Decline</button>
                                    </div>
                                    
                                ";
                            }elseif($is_approved==0){
                                $approval_msg="
                                    <div class='col-md-4'>
                                        <span style='font-size:10px;color:red'>Declined</span>
                                    </div>
                                    <div class='col-md-4'>
                                        <button class='btn btn-primary'". '  onclick="decideOnResearchApproval(2,'.$research_id.','.$researcher_id.','."'approvalDiv$i'".')"'.">Make Pending</button>
                                    </div>
                                    <div class='col-md-4'>
                                        <button class='btn btn-success'". '  onclick="decideOnResearchApproval(1,'.$research_id.','.$researcher_id.','."'approvalDiv$i'".')"'.">Approve</button>
                                    </div>
                                ";
                            }elseif($is_approved==2){
                                $approval_msg="
                                    <div class='col-md-4'>
                                        <span style='font-size:10px;color:grey'>Not yet approved</span>
                                    </div>
                                    <div class='col-md-4'>
                                        <button class='btn btn-danger'". '  onclick="decideOnResearchApproval(0,'.$research_id.','.$researcher_id.','."'approvalDiv$i'".')"'.">Decline</button>
                                    </div>
                                    <div class='col-md-4'>
                                        <button class='btn btn-success'". '  onclick="decideOnResearchApproval(1,'.$research_id.','.$researcher_id.','."'approvalDiv$i'".')"'.">Approve</button>
                                    </div>
                                ";
                            }
                ?>
                        <div class="row" style="margin-top:45px;background:<?php echo $approval_background;?>;padding:10px;border-right:<?php echo $approval_border;?>;border-left:<?php echo $approval_border;?>">
                            <div class="col-md-3">
                                <img src="<?php echo "../$researcher_pix";?> " alt="" class="img img-responsive" height="18px">
                                <div><?php echo $researcher_name;?></div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <div class='row'><span style="color:grey;">Research title</span>: <?php echo $research_title;?></div>
                                <div class='row' style="margin-top:25px;" id="approvalDiv<?php echo $i;?>"><?php echo $approval_msg;?></div>
                                <?php $enc_research_id=base64_encode($research_id);?>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <a href="research_details.php?research_id=<?php echo $enc_research_id;?>" style="color:brown;">View details</a>
                                </div>
                            </div>
                        </div>
                        
                
                <?php
                        $research->update_research_creation_is_seen($research_id);
                        }
                    }
                ?>
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
    <script src="../app/assets/js/select2.full.min.js"></script>

    <script>
        function decideOnResearchApproval(is_approved,researchId,researcherId,approvalDivId) {
            
            var formData = new FormData();
            formData.append("decide_on_research_approval",1);
            formData.append("research_id",researchId);
            formData.append("researcher_id",researcherId);
            formData.append("is_approved",is_approved);
            formData.append("approval_div_id",approvalDivId);

            console.log(approvalDivId);

            
            $.ajax({
                url:"../parser/coordinator_parser.php",
                data:formData,
                type:"post",
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    data=JSON.parse(data);
                    if(data.status==1){
                        $("#"+approvalDivId).html(data.approval_details)
                    }
                    alert(data.message);
                }
            })
        }
    </script>

  </body>

</html>
