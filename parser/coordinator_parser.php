<?php
    include ("../app/init.php");

    if(isset($_POST['approve_research'])){
        $research_id=$_POST['research_id'];
        exit($coordinator->approve_research($research_id));
    }

    if(isset($_POST['disapprove_research'])){
        $research_id=$_POST['research_id'];
        exit($coordinator->disapprove_research($research_id));
    }

    if(isset($_POST['report_research'])){
        $research_id=$_POST['research_id'];
        exit($coordinator->report_research($research_id));
    }

    if(isset($_POST['report_researcher'])){
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $coordinator_id=$_POST['coordinator_id'];
        if(isset($_POST['report'])){
            $report=$_POST['report'];
            $coordinator->report_research($research_id,$researcher_id,$coordinator_id,$report);
        } 
    }

    if(isset($_POST['decide_on_research_approval'])){
        $is_approved=$_POST['is_approved'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $approval_div_id=$_POST['approval_div_id'];

        $coordinator->decide_on_research_approval($is_approved,$research_id,$researcher_id);

        $approval_msg="";
        $message="";

        if($is_approved==1){
            $message="Research successfully approved";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:blue'>Approved</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-primary'". '  onclick="decideOnResearchApproval(2,'.$research_id.','.$researcher_id.','."'$approval_div_id'".')"'.">Make Pending</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-danger'". '  onclick="decideOnResearchApproval(0,'.$research_id.','.$researcher_id.','."'$approval_div_id'".')"'.">Decline</button>
                </div>
                
            ";
        }elseif($is_approved==0){
            $message="Research successfully declined";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:red'>Declined</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-primary'". '  onclick="decideOnResearchApproval(2,'.$research_id.','.$researcher_id.','."'$approval_div_id'".')"'.">Make Pending</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-success'". '  onclick="decideOnResearchApproval(1,'.$research_id.','.$researcher_id.','."'$approval_div_id'".')"'.">Approve</button>
                </div>
            ";
        }elseif($is_approved==2){
            $message="Research successfully placed on pending list";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:grey'>Not yet approved</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-danger'". '  onclick="decideOnResearchApproval(0,'.$research_id.','.$researcher_id.','."'$approval_div_id'".')"'.">Decline</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-success'". '  onclick="decideOnResearchApproval(1,'.$research_id.','.$researcher_id.','."'$approval_div_id'".')"'.">Approve</button>
                </div>
            ";
        }

        exit(json_encode(["status"=>1,"message"=>$message,"approval_details"=>$approval_msg]));

    }

    if(isset($_POST['fetch_more_researcher'])){
        
    }

    if(isset($_POST['fetch_more_researches'])){
        
    }

    if(isset($_POST['fetch_more_approved_researches'])){
        
    }

    if(isset($_POST['fetch_more_disapproved_researches'])){
        
    }

    if(isset($_POST['fetch_more_reported_researchers'])){
        
    }



    

?>