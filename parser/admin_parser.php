<?php
    include("../app/init.php");
    // $_SESSION['email']="koikidamilare@gmail.com";

    if(isset($_POST['add_user'])){
        $surname=$_POST['surname'];
        $other_names=$_POST['other_names'];
        $email=$_POST['email'];
        $password=md5($_POST['password']);
        
    
        if(!empty($surname)&&!empty($other_names)&&!empty($email)&&!empty($password)){
            if(isset($_POST['user_role'])){
                $user_role=$_POST['user_role'];
                if($user_role==0 or $user_role==1){
                    $result=$admin->add_user($surname,$other_names,$email,$password,$user_role,$_SESSION['email']);
                    exit($result);
                }else{
                    exit(json_encode(["status"=>0,"message"=>"Please select a vaild role"]));
                }
            }else{
                exit(json_encode(["status"=>0,"message"=>"Please fill all fields"]));
            }
        }else{
            exit(json_encode(["status"=>0,"message"=>"Please fill all fields"]));
        }
    }

    if(isset($_POST['login'])){
        $email=$_POST['email'];
        $password=md5($_REQUEST['password']);
    
        if(!empty($email)&&!empty($password)){
            $result=$admin->login($email, $password);
            // exit($password);
        }else{
            exit(json_encode(["status"=>0,"message"=>"Please fill all fields"]));
        }
    }

    if(isset($_POST['remove_user'])){
        $user_id=$_POST['user_id'];

        exit($admin->remove_user($user_id,$_SESSION['email']));
    }

    if(isset($_POST['remove_research'])){
        $research_id=$_POST['research_id'];

        exit($admin->remove_research($research_id,$_SESSION['email']));
    }

    if(isset($_POST['remove_admin'])){
        $admin_id=$_POST['admin_id'];

        exit($admin->remove_admin($admin_id,$_SESSION['email']));
    }

    if(isset($_POST['decide_on_coordinator_approval'])){
        // exit("here");
        $coordinator_id=$_POST['coordinator_id'];
        $approval_div_id=$_POST['approval_div_id'];
        $is_approved=$_POST['approval'];
        $admin->decide_on_coordinator_approval($is_approved,$coordinator_id,$_SESSION['email']);

        $approval_msg="";
        $message="";
        if($is_approved==1){
            $message="Coordination role request accepted";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:blue'>Approved</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-primary'". '  onclick="decideOnCoordinationRoleRequest(2,'.$coordinator_id.','."'$approval_div_id'".')"'.">Make Pending</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-danger'". '  onclick="decideOnCoordinationRoleRequest(0,'.$coordinator_id.','."'$approval_div_id'".')"'.">Decline</button>
                </div>
                
            ";
        }elseif($is_approved==0){
            $message="Coordination role request declined";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:red'>Declined</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-primary'". '  onclick="decideOnCoordinationRoleRequest(2,'.$coordinator_id.','."'$approval_div_id'".')"'.">Make Pending</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-success'". '  onclick="decideOnCoordinationRoleRequest(1,'.$coordinator_id.','."'$approval_div_id'".')"'.">Approve</button>
                </div>
            ";
        }elseif($is_approved==2){
            $message="Coordination role request placed on pending list";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:grey'>Not yet approved</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-danger'". '  onclick="decideOnCoordinationRoleRequest(0,'.$coordinator_id.','."'$approval_div_id'".')"'.">Decline</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-success'". '  onclick="decideOnCoordinationRoleRequest(1,'.$coordinator_id.','."'$approval_div_id'".')"'.">Approve</button>
                </div>
            ";
        }

        exit(json_encode(["status"=>1,"message"=>$message,"approval_details"=>$approval_msg]));
    }

    if(isset($_POST['ignore_research_report'])){
        $report_id=$_POST['report_id'];
        exit($admin->ignore_research_report($report_id,$_SESSION['email']));
    }

    if(isset($_POST['ignore_researcher_report'])){
        $report_id=$_POST['report_id'];
        exit($admin->ignore_researcher_report($report_id,$_SESSION['email']));
    }


    if(isset($_POST['add_admin'])){
        $surname=$_POST['surname'];
        $other_names=$_POST['other_names'];
        $email=$_POST['email'];
        $password=md5($_POST['password']);

        if(!empty($surname)&&!empty($other_names)&&!empty($email)&&!empty($password)){
            $result=$admin->add_admin($surname,$other_names,$email,$password,$_SESSION['email']);
            exit($result);
        }else{
            exit(json_encode(["status"=>0,"message"=>"Please fill all fields"]));
        }
    }



?>