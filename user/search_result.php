<?php
    include ("../app/init.php");
    if(!isset($_SESSION['email'])){
    $master->redirect("../index.php");
    }

    $all_researches=[];
    if(!isset($_GET['research_search'])){
        $master->redirect("index.php");
        exit();
    }else{
        $research_title=$_GET['research_search'];
        $all_researches=$research->search($research_title);
    }

    $follower_id=$user_id;
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
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> -->

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
        <div class="row">
            <div class="col-md-8 mx-auto mb-5">
                <a class="btn btn-primary" href="add_research.php">Add New Research</a>
            </div>
            <div class="col-md-8 mx-auto mb-5">
            <?php if(isset($_GET['research_search'])){?>
                <h4 style="font-weight:bold">Search result for <?php echo $_GET['research_search'];?></h4><?php } ?>
                <h6><?php echo count($all_researches);if(count($all_researches)==1)echo "result";else echo "results"?> for found</h6>
            </div>
            <div class="col-md-8 mx-auto mb-5" style="margin-top:15px;">
                <?php
                  $i=0;
                  if (!empty($all_researches)) {
                  foreach ($all_researches as $researche) {
                    $i++;
                    $title=$researche['research_title'];
                    $description=$researche['research_description'];
                    $research_id=$researche['research_id'];
                    $researcher_id=$researche['researcher_id'];
                    $is_approved=$researche['is_approved'];
                    $researcher_pix=$research->get_user_data($researcher_id)['profile_pix'];
                    $researcher_name=$research->get_user_data($researcher_id)['surname']." ".$research->get_user_data($researcher_id)['other_names'];
                    $researcher_user_role=$research->get_user_data($researcher_id)['user_role'];
                    $logged_in_user_role=$research->get_user_data($user_id)['user_role'];


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

                    $researcher_contribution_text=$research->get_contributor_text($research_id,$user_id);
                    $research_contribution_text_id=0;
                    $researcher_contribution_text_content="";
                    if(!empty($researcher_contribution_text)){
                      $research_contribution_text_id=$researcher_contribution_text['text_id'];
                      $researcher_contribution_text_content=$researcher_contribution_text['text'];
                    }
?>
<div style="margin-bottom:110px" class="researchDiv" id="researchDiv<?php echo $i; ?>">
  <div class="row">
    <div class="col-md-1">
      <img src="../<?php echo $researcher_pix;?>" class="" height="18">
      <div style="font-size:10px;"><strong title="<?php echo $researcher_name;?>"><?php if(strlen($researcher_name)>10){echo substr($researcher_name,0,10)."...";}else{ echo $researcher_name; }?></strong></div>
    </div>
    <div class="col-md-10"><h3 class="research-title"> <?php echo ucwords(strtolower($title))?></h3></div>
    <div class="col-md-1">
      <?php
        if($researcher_id==$user_id && $logged_in_user_role!=1){
      ?>
        <div class="dropdown show">
          <a class="btn" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;"><i class="fa fa-ellipsis-v"> </i></a>
          <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
          <?php if($is_approved==1){ ?>
            <button class="nav-link js-scroll-trigger btn showTextEditor" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>">Add write-up</button>
          
            <button data-toggle="modal" data-target="#addResearchFileModal" class="nav-link js-scroll-trigger btn showfileUploadModal" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="openResearchFileFormModal(<?php echo $research_id; ?>,<?php echo $researcher_id; ?>,'t_r_f_msg<?php echo $i;?>')">Add file</button>
            <?php } ?>
            <button data-toggle="modal" data-target="#editResearchModal" class="nav-link js-scroll-trigger btn showfileUploadModal" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="openEditResearchFormModal(<?php echo $research_id; ?>,<?php echo $researcher_id; ?>,this)">Edit Research</button>

            <button class="nav-link js-scroll-trigger btn" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="removeResearch(<?php echo $research_id;?>,'researchDiv<?php echo $i; ?>')">Remove research</button>
            
          </div>
        </div>
        
      <?php
        }elseif($researcher_id==$user_id && $logged_in_user_role==1){
      ?>
        <div class="dropdown show">
          <a class="btn" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;"><i class="fa fa-ellipsis-v"> </i></a>
          <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
          <?php if($is_approved==1){ ?>
            <button class="nav-link js-scroll-trigger btn showTextEditor" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>">Add write-up</button>

            <button data-toggle="modal" data-target="#addResearchFileModal" class="nav-link js-scroll-trigger btn showfileUploadModal" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="openResearchFileFormModal(<?php echo $research_id; ?>,<?php echo $researcher_id; ?>,'t_r_f_msg<?php echo $i;?>')">Add file</button>
            <?php } ?>
            <button data-toggle="modal" data-target="#editResearchModal" class="nav-link js-scroll-trigger btn showfileUploadModal" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="openEditResearchFormModal(<?php echo $research_id; ?>,<?php echo $researcher_id; ?>,this)">Edit Research</button>

            <button class="nav-link js-scroll-trigger btn" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>" onclick="removeResearch(<?php echo $research_id;?>,'researchDiv<?php echo $i; ?>')">Remove research</button>
            
          </div>
        </div>
      
      <?php
        }elseif($logged_in_user_role==1 && $researcher_id!=$user_id){
      ?>
        <div class="dropdown show">
          <a class="btn" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;"><i class="fa fa-ellipsis-v"> </i></a>
          <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">
            <button data-toggle="modal" data-target="#reportModal" class="nav-link js-scroll-trigger btn" type="button" style="color:black;background:none;text-transform: none;" onclick="reportResearcher(<?php echo $researcher_id; ?>)">Report researcher</button>

            <button data-toggle="modal" data-target="#reportModal" class="nav-link js-scroll-trigger btn" type="button" style="color:black;background:none;text-transform: none;" onclick="reportResearch(<?php echo $research_id; ?>)">Report research</button>
            
          </div>
        </div>

      <?php
        }
      ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-10 mx-auto text-center"> <h6 class="research-description"><?php echo ucfirst(strtolower($description))?></h6></div>
  </div>
  <?php if($is_approved==1){ ?>
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

      if($research->researcher_has_commented($research_id,$user_id)){
        $comment_class="fa-comment";
        $comment_color="red";
      }else{
        $comment_class="fa-comment-o";
        $comment_color="blue";
      }

      if($research->researcher_has_contributed($research_id,$user_id)){
        $contribution_color="red";
      }else{
        $contribution_color="blue";
      }

      if($researcher->has_sent_follow_research_request($research_id,$user_id)){
        $follow_color="brown";
      }elseif($researcher->is_following_research($research_id,$user_id)){
        $follow_color="red";
      }else{
        $follow_color="blue";
      }

      $is_authorized=0;
      if($researcher->is_authorized($research_id,$user_id)){
        $is_authorized=1;
      }

      
    ?>
      <div class="col-md-1"></div>
      <div class="col-md-4">
        <span id="t_r_f_msg<?php echo $i;?>" onclick="showResearchFilesModal(<?php echo $research_id;?>,<?php echo $user_id;?>,0)" data-toggle="modal" data-target="#newModal" style="cursor:pointer;color:blue;border-bottom:1px solid blue;">
          <?php echo $t_r_f_msg;?>
        </span>
      </div>
      <div class="col-md-6">
      </div>
      <div class="col-md-1">

        <?php
          if($user_id==$researcher_id){
        ?>
          <div class="dropdown show">
            <a class="btn" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;"><i class="fa fa-share"></i></a>
            <div class="dropdown-menu" araia-labelledby="dropdownMenuButton">

              <button class="nav-link js-scroll-trigger btn publicShare" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>">To public</button>

              <button data-toggle="modal" data-target="#fewShareModal" class="nav-link js-scroll-trigger btn fewShare" type="button" style="color:black;background:none;text-transform: none;" research-id="<?php echo $research_id; ?>">To selected people</button>
            </div>
          </div>
        <?php    
          }
        ?>
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
    <?php
      if($research->is_shared_to_this_other_researcher($research_id,$user_id)) echo "<div class='col-md-4'><b style='color:grey'>Shared to you</b></div>";
    ?>
  </div>
        <?php }?>
  <div class="row" style='padding:5px 0px 0px 10px'>
    <div class="col-md-1"></div>
    <div class='col-md-4'><b style='color:grey'><span class="numb-collab"><?php echo $total_collaborators; ?> 
    <?php if($total_collaborators==1) echo "Collaborator";else echo "Collaborators";?></span></b> </div>
  </div>
  <?php if($is_approved==1){ ?>
  <div class="row" style="margin-top:25px;">
    <div class="col-md-2"></div>

    <div class="col-md-3">
      <i class="fa <?php echo $comment_class;?> addComment" data-toggle="modal" data-target="#myModal" style="cursor:pointer;color:<?php echo $comment_color;?>;" research-id="<?php echo $research_id; ?>" researcher-id="<?php echo $researcher_id; ?>" is-authorized="<?php echo $is_authorized;?>" id="addComment<?php echo $i;?>"> <?php echo $total_comments?></i>
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
    <?php }elseif($is_approved==2){ echo "<span style='color:grey'>Not yet approved</span>";}elseif($is_approved==0){ echo "<span style='color:red'>Declined</span>";}?>
</div>

<?php
                  }
                }else{
                  echo "<div><span style='color;grey'>No research added yet, be the first to</span> <a href='add_research.php' style='color:blue;border-bottom:1px solid blue'>add a research</a></div>";
                }
                ?>
            </div>
        </div>

        
      </div>
    </section>




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
              <div id="commentFormDiv">
                <form id="commentForm" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="comment"></label>
                    <textarea id="comment" cols="100" rows="5" class="form-control" style="resize:none;" placeholder="Add Comment"></textarea>
                  </div>
                  <input type="hidden" name="" id="comment_research_id">
                  <input type="hidden" name="" id="comment_researcher_id">
                  <input type="hidden" name="" id="comment_by" value="<?php echo $user_id;?>">


                  <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary" id="commentBtn">
                  </div>
                </form>
              </div>
              <div id="contributionFormDiv" class="ml-auto">
                <div class="row" style="width:100%">
                  <div class="col-md-5"><input type="radio" name="contrRadio[]" id="showContributionInput"/> Write-up Contribution</div>
                  <div class="col-md-2"></div>
                  <div class="col-md-5"><input type="radio" name="contrRadio[]" id="showContributionFile"/> File Contribution</div>
                </div>
                <form id="contributionTextForm" style="display:none">
                  <div class="form-group">
                    <label for="contribution_text"></label>
                    <textarea name="contribution_text" id="contribution_text" cols="100" rows="5" class="form-control" style="resize:none;" placeholder="Add Write-up"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="contTextreferences">Add references (Optional)</label>
                    <select class="form-control" multiple style="width:100%" id="contTextreferences">
                    
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary" id='contTextBtn'>
                  </div>

                  <input type="hidden" id="contributionTextId">
                  <input type="hidden" id="contributionResearchId">
                  <input type="hidden" id="contributionResearcherId">



                </form>
                <form id="contributionFileForm" enctype="multipart/form-data" style="display:none">
                  <div class="form-group">
                    <label for="contributionFileField">Select File</label>
                    <input type="file" name="research_contribution_file" id="contributionFileField" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="contributionFileRefernces">Add references (Optional)</label>
                    <select class="form-control" multiple style="width:100%" id="contributionFileRefernces">
                    <?php
                        foreach ($all_references_avail as $ref_avail) {
                          $ref_id=$ref_avail["reference_id"];
                          $research_topic=$ref_avail["reference"];
                          echo "<option datavalue='$ref_id' reference_name='$research_topic'>$research_topic</option>";
                        }
                    ?>
                    </select>

                  </div>
                  <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary" id="contFileBtn">
                  </div>

                </form>
              </div>
            </div>
            <div id="notAuthorizedDiv" class="mr-auto"></div>
          </div>

          

          
        </div>
      </div>
    </div>



    <!-- Few share modal -->
    <div id="fewShareModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Share to other researchers</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body" id="modalBody-users">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                  
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

    <!-- New Modal -->
    <div id="addResearchFileModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="display:none"></h4>
          </div>
          <div class="modal-body mx-auto" id="" style="padding:7px">
            <form enctype="multipart/form-data" id="researchFileForm">
              <div class="form-group">
                <input type="file" class="form-control" id="researchFileField">
              </div>
              <div class="form-group">
                <label for="researchFileRefernces">Add references (Optional)</label>
                <select class="form-control" multiple style="width:100%" id="researchFileRefernces">
                <?php
                    foreach ($all_references_avail as $ref_avail) {
                      $ref_id=$ref_avail["reference_id"];
                      $research_topic=$ref_avail["reference"];
                      echo "<option datavalue='$ref_id' reference_name='$research_topic'>$research_topic</option>";
                    }
                ?>
                </select>
              </div>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-primary">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                  
          </div>
        </div>
      </div>
    </div>


    <!-- Report Modal -->
    <div id="reportModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="reportModalTitle"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body mx-auto" id="" style="padding:7px">
            <form enctype="multipart/form-data" id="reportResearchForm" onsubmit="submitReportForm(0);return false;">
              <div class="form-group">
                <input type="text" class="form-control" id="reportResearchField" placeholder="Enter report">
              </div>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-primary">
              </div>
            </form>

            <form enctype="multipart/form-data" id="reportResearcherForm" onsubmit="submitReportForm(1);return false;">
              <div class="form-group">
                <input type="text" class="form-control" id="reportResearcherField" placeholder="Enter report">
              </div>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-primary">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                  
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Research Modal -->
    <div id="editResearchModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Research</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body mx-auto" id="" style="padding:7px">
            <form enctype="multipart/form-data" id="EditResearchForm">
              <div class="form-group">
                <label for="editTitle">Title</label>
                <input type="text" name="title" class="form-control" id="editTitle" placeholder="Title"/>
              </div>
              <div class="form-group">
                <label for="editDescription">Description</label>
                <input type="text" name="description" class="form-control"  id="editDescription" placeholder="Description"/>
              </div>
              <div class="form-group">
                  <label for="editCollaborators">Add collaborators (Optional)</label>
                  <select name="collaborators[]" class="form-control" id="editCollaborators" multiple style="width:100%">

                  </select>
              </div>
              <div class="form-group">
                <input type="submit" value="Add Reasearch" class="btn btn-primary" id="addResearchBtn"/>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                  
          </div>
        </div>
      </div>
    </div>

    

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
    <script src="../assets/ckeditor/ckeditor.js"></script>
    <script src="../assets/js/bootbox.min.js"></script>


  </body>
  <script>
    $(".references").select2({
      placeholder:"Add References",
      tags:true
    });
    $("#researchFileRefernces,#contributionFileRefernces").select2({
      placeholder:"Add References",
      tags:true
    })

    $("#editCollaborators").select2({
      placeholder:"Add Collaborators",
      tags:true
    })
  </script>
  <script>
    $(".write-up-field").each(function(){
      fieldName=$(this).attr("name");
      CKEDITOR.replace(fieldName);
    })

    $(".write-up-form").submit(function(e) {
      e.preventDefault();
      formId=$(this).attr("id");
      writeUpField=$("#"+formId+" textarea");
      writeUpFieldName=writeUpField.attr("name");
      writeUp=CKEDITOR.instances[writeUpFieldName].getData();
      writeUpBtn=$("#"+formId+" .writeUpBtn");
      
      text_id=writeUpField.attr("text-id");
      researchId=$(this).attr("research-id");
      researcherId=$(this).attr("researcher-id");

      writeUpBtn.val("Please! wait...")

      
      formData=new FormData();
      formData.append("add_research_text",1)
      formData.append("research_id",researchId);
      formData.append("researcher_id",researcherId);
      formData.append("text",writeUp);
      formData.append("text_id",text_id);

      $(this).find(".references :selected").each(function(){
        try{
            formData.append('references[]',$(this).val());
            formData.append('references_ids[]',$(this).attr('datavalue'));
        }catch(e){

        }
      });

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
          writeUpBtn.val("Submit write-up");
          if(data.status==1){
            alert(data.message)
          }else{
            alert(data.message)
          }
        }
      })
    })
  </script>

    <script>
      CKEDITOR.replace("contribution_text");

      $("#commentForm").submit(function(e){
        e.preventDefault();
        formData=new FormData();
        $("#commentBtn").val("Please! wait...");
        researchId=$("#comment_research_id").val();
        researcherId=$("#comment_researcher_id").val();
        commentBy=$("#comment_by").val();
        comment=$("#comment").val();



        
        formData.append("add_research_comment",1);
        formData.append("research_id",researchId);
        formData.append("researcher_id",researcherId);
        formData.append("comment_by",commentBy);
        formData.append("comment",comment);



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
            $("#commentContents").html(data.all_comments_made)
            $("#"+addCommentId).css("color","red").html(data.total_comments);
            
            $("#commentBtn").val("Submit");
          }
        })
      })

      $("#showContributionInput").click(function(e){
        if($(this).is(":checked")){
          $("#contributionTextForm").show();
          $("#contributionFileForm").hide();
          $("#contributionContents").hide();
          $("#reshowContCont").show();
        }
      })

      $("#showContributionFile").click(function(e){
        if($(this).is(":checked")){
          $("#contributionTextForm").hide();
          $("#contributionFileForm").show();
          $("#contributionContents").hide();
          $("#reshowContCont").show();
        }
      })

      $("#contTextreferences").select2({
        placeholder:"Add references",
        tags:true,
        width:"100%"
      })

    var contId=0;
    var contResearchId=0;
    var contResearcherId=0;

    $(".contribute").click(function(){
      $("#comContTitle").html("Add contributions");
      researchId=$(this).attr("research-id");
      researcherId=$(this).attr("researcher-id");
      followerId=<?php echo $user_id;?>;
      
      contResearchId=researchId;
      contResearcherId=researcherId;

      
      contId=$(this).attr("id");


      // $("#contribution_text").text();
      CKEDITOR.instances["contribution_text"].setData($(this).attr("contribution-text"))
      $("#contributionTextId").val($(this).attr("contribution-text-id"));
      $("#contributionResearchId").val($(this).attr("research-id"));
      $("#contributionResearcherId").val($(this).attr("researcher-id"));

      $("#contTextreferences").html($(this).attr("contribution_text_references_html"));

      $("#contributionContents, #contributionFormDiv").show();
      $("#commentContents, #commentFormDiv").hide();

      $("#contributionTextForm, #contributionFileForm, #reshowContCont").hide();
      $("#showContributionInput").prop("checked",false);
      $("#showContributionFile").prop("checked",false);
      $("#contributionTextForm, #contributionFileForm").trigger("reset");

      $("#CommentContributionDiv").hide();
      $("#notAuthorizedDiv").hide();
      if($(this).attr("is-authorized")==1){
        $("#CommentContributionDiv").show();
        $("#notAuthorizedDiv").hide();       
      }else{
        $("#notAuthorizedDiv").show().html("You are not authorized to contribute to research. <span style='color:blue;border-bottom:1px solid blue;cursor:pointer;' onclick='followResearch("+researchId+","+researcherId+","+followerId+")'>Click to send follow request</span>");
        $("#CommentContributionDiv").hide();
      }
      
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

    $("#contributionTextForm").submit(function(e){
      e.preventDefault();
      formData=new FormData();
      writeUp=CKEDITOR.instances["contribution_text"].getData();
      $(this).find("#contTextreferences :selected").each(function(){
        try{
          formData.append('references[]',$(this).val());
          formData.append('references_ids[]',$(this).attr('datavalue'));
        }catch(e){

        }
      });

      $("#contTextBtn").val("Please! wait");

      
      formData.append('contribute_research_text',1);
      
      formData.append('text',writeUp);
      formData.append('text_id',$("#contributionTextId").val());
      formData.append('research_id',$("#contributionResearchId").val());
      formData.append('researcher_id',$("#contributionResearcherId").val());
      formData.append('contributor_id',"<?php echo $user_id;?>");

      $.ajax({
        url:"../parser/research_parser.php",
        data:formData,
        type:"post",
        contentType: false,
        cache: false,
        processData: false,
        success:function(data){
          console.log(data);
          $("#contTextBtn").val("Submit");
          data=JSON.parse(data);
          if(data.status==1){
            $("#"+contId).html(data.total_contributions).css("color","red");
            $("#contributionTextForm").trigger("reset");
            $("#contributionTextForm").hide();
            $("#showContributionInput").prop("checked",false);
            $("#contributionContents").html(data.contribution_content).show();
            $("#reshowContCont").hide();
          }
          alert(data.message);
          // $("#commentContents").html(data)
        }
      })
    })
  </script>

  <script>
    var addCommentId=0;
    $(".addComment").click(function(){
      $("#comContTitle").html("Add comments");
      $("#contributionContents, #contributionFormDiv").hide();
      $("#commentContents, #commentFormDiv").show();

      addCommentId=$(this).attr("id");

      researchId=$(this).attr("research-id")
      researcherId=$(this).attr("researcher-id");
      followerId=<?php echo $user_id;?>;
      formData=new FormData();
      formData.append("fetch_comments",1);
      formData.append("research_id",researchId);

      $("#comment_research_id").val(researchId)
      $("#comment_researcher_id").val(researcherId)

      $("#notAuthorizedDiv").hide();
      if($(this).attr("is-authorized")==1){
        $("#CommentContributionDiv").show();
        $("#notAuthorizedDiv").hide();       
      }else{
        $("#notAuthorizedDiv").show().html("You are not authorized to comment on research. <span style='color:blue;border-bottom:1px solid blue;cursor:pointer;' onclick='followResearch("+researchId+","+researcherId+","+followerId+")'>Click to send follow request</span>");
        $("#CommentContributionDiv").hide();
      }


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

    
    var followId=0;
    $(".follow").click(function(){
      followId=$(this).attr("id")
      researchId=$(this).attr("research-id")
      researcherId=$(this).attr("researcher-id")
      followerId=$(this).attr("follower-id")

      followResearch(researchId,researcherId,followerId);

      
    })

    $(".publicShare").click(function(){
      researchId=$(this).attr("research-id")
      formData=new FormData();
      formData.append("share_research",1);
      formData.append("share_to_public",1);
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
            data=JSON.parse(data);
            alert(data.message)
            // $("#commentContents").html(data)
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
    })

    $(".fewShare").click(function(){
      researchId=$(this).attr("research-id")
      formData=new FormData();
      formData.append("fetch_users_to_add_follow",1);
      formData.append("research_id",researchId);

      $.ajax({
        url:"../parser/user_parser.php",
        data:formData,
        type:"post",
        contentType: false,
        cache: false,
        processData: false,
        success:function(data){
            console.log(data);
            $("#modalBody-users").html(data)
        }
      })
    })

    $(".showTextEditor").click(function(){
      parentObj=$(this).parents(".researchDiv");
      parentId=parentObj.attr("id");
      console.log(parentObj);
      
      researchWriteUpObj=$("#"+parentId+" .research-write-up");
      researchWriteUp=researchWriteUpObj.html();
      researchWriteUpDiv=$("#"+parentId+" .research-write-up-div");
      composeWriteUpDiv=$("#"+parentId+" .compose-write-up-div");
      researchWriteUpDiv.hide();
      composeWriteUpDiv.show();

    })

    function showModalContents(id,otherId){
      $("#"+id).hide()
      $("#contributionContents").show();
      $("#"+otherId).hide();
      $("#contributionFileForm").hide();
      $("#showContributionInput").prop("checked",false);
      $("#showContributionFile").prop("checked",false);

    }

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

    function followResearch(researchId,researcherId,followerId) {
      formData=new FormData();
      formData.append("seek_research_follow",1);
      formData.append("research_id",researchId);
      formData.append("researcher_id",researcherId);
      formData.append("follower_id",followerId);

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
            alert(data.message);
            $("#"+followId).css("color","brown");
        }
      })
    }

  </script>
<!-- fetch_all_users() -->

<script>
  var globResearchId=0;
  var globResearcherId=0;
  var globTrf=0;
  function openResearchFileFormModal(researchId,researcherId,trf) {
    globResearchId=researchId;
    globResearcherId=researcherId;
    globTrf=trf;
  }
  $("#researchFileForm").submit(function(e){
    e.preventDefault();
    fileData = $("#researchFileField")[0].files[0];
    formData=new FormData();
    formData.append("research_id",globResearchId);
    formData.append("researcher_id",globResearcherId);
    formData.append("add_research_file",1);
    formData.append("research_file",fileData);

    
    $("#researchFileRefernces :selected").each(function(){
      try{
          formData.append('references[]',$(this).val());
          formData.append('references_ids[]',$(this).attr('datavalue'));
      }catch(e){

      }
    });

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
          if(data.status==1){
            $("#"+globTrf).html(data.total_research_files);
            $("#researchFileForm").trigger("reset");
            $("#addResearchFileModal").modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
          }
          
          alert(data.message);
      }
    })
  })

  // Contribution File Form Submission
  $("#contributionFileForm").submit(function(e){
    e.preventDefault();

    $("#contFileBtn").val("Please! wait");

    fileData = $("#contributionFileField")[0].files[0];
    contributorId=<?php echo $user_id;?>;

    formData=new FormData();
    formData.append("research_id",contResearchId);
    formData.append("researcher_id",contResearcherId);
    formData.append("contributor_id",contributorId);
    formData.append("research_contribution_file",fileData);
    formData.append("contribute_research_file",1);
    
    $("#contributionFileRefernces :selected").each(function(){
      try{
          formData.append('references[]',$(this).val());
          formData.append('references_ids[]',$(this).attr('datavalue'));
      }catch(e){

      }
    });

    $.ajax({
      url:"../parser/research_parser.php",
      data:formData,
      type:"post",
      contentType: false,
      cache: false,
      processData: false,
      success:function(data){
          console.log(data);
          $("#contFileBtn").val("Submit");
          data=JSON.parse(data);
          if(data.status==1){
            $("#"+contId).html(data.total_contributions).css("color","red");
            $("#contributionFileForm").trigger("reset");
            $("#contributionFileForm").hide();
            $("#showContributionFile").prop("checked",false);
            $("#contributionContents").html(data.contribution_content).show();
            $("#reshowContCont").hide();
          }
          alert(data.message);
      }
    })
  })
</script>

<script>
var reportResearchId=0;
var reportResearcherId=0;

  function reportResearch(researchId) {
    reportResearchId=researchId;
    $("#reportResearchForm").show();
    $("#reportResearcherForm").hide();
    $("#reportModalTitle").html("Report research to admin");
  }

  function reportResearcher(researcherId) {
    reportResearcherId=researcherId;
    $("#reportResearchForm").hide();
    $("#reportResearcherForm").show();
    $("#reportModalTitle").html("Report researcher to admin");
  }


  function submitReportForm(what) {
    if(what==0){
      what="report_research";
      whatId="research_id";
      id=reportResearchId;
      whatForm="reportResearchForm";
      whatReport=$("#reportResearchField").val();
    }
    if(what==1){
      what="report_researcher";
      whatId="researcher_id";
      id=reportResearcherId;
      whatForm="reportResearcherForm";
      whatReport=$("#reportResearcherField").val();
    }
    formData=new FormData();
    formData.append(what,1)
    formData.append(whatId,id);
    formData.append("report",whatReport);
    formData.append("reporter_id",<?php echo $user_id;?>);



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
            if(data.status==1){
              $("#"+whatForm).trigger("reset");
              $("#reportModal").modal('hide');
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();
            }
            alert(data.message);
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
              url:"../parser/research_parser.php",
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

  var edResearchId=0;
  var edResearcherId=0;
  var editResearchParentId=0
  function openEditResearchFormModal(researchId,researcherId,obj) {
    edResearchId=researchId;
    edResearcherId=researcherId;
    parentId=$(obj).parents(".researchDiv").attr("id");
    editResearchParentId=parentId;
    console.log(parentId);

    formData=new FormData();
    formData.append("fetch_research_details",1);
    formData.append("research_id",edResearchId);

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
          $("#editTitle").val(data.research_title)
          $("#editDescription").val(data.research_description);
          $("#editCollaborators").html(data.research_collaborators)
      }
    })
    
  }

  $("#EditResearchForm").submit(function(e){
    e.preventDefault();
    editTitle=$("#editTitle").val();
    editDescription=$("#editDescription").val();
    formData=new FormData();
    formData.append("edit_research",1)
    formData.append("title",editTitle)
    formData.append("description",editDescription)
    formData.append("research_id",edResearchId)
    formData.append("researcher_id",edResearcherId)


    countCollab=0;
    $("#editCollaborators :selected").each(function(){
      try{
        countCollab++;
          formData.append('collaborators[]',$(this).val());
      }catch(e){

      }
    });
    

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
        $("#"+editResearchParentId).find(".research-title").html(editTitle);
        $("#"+editResearchParentId).find(".research-description").html(editDescription);
        if(countCollab==1) $("#"+editResearchParentId).find(".numb-collab").html(countCollab+" Collaborator");
        else  $("#"+editResearchParentId).find(".numb-collab").html(countCollab+" Collaborators");
        alert(data.message)

      }
    })
  })
</script>

</html>
