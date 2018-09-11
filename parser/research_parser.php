<?php
    include ("../app/init.php");

    if(isset($_POST['add_research'])){
        $title=$_POST['title'];
        $description=$_POST['description'];
        $researcher_id=$_SESSION['user_id'];
        if(!empty($title)&&!empty($description)){
            if($research->research_exists($title,$researcher_id)){
                exit(json_encode(["status"=>0,"message"=>"Research already exists"]));
            }
            $result=json_decode($research->add($title,$description,$researcher_id),true);
            if($result['status']==1){
                $research_id=$result['research_id'];
                if(isset($_POST['collaborators']) && !empty($_POST['collaborators'])){
                    $collaborators=(array)$_POST['collaborators'];
                    foreach ($collaborators as $collaborator) {
                        $research->add_collaborator($research_id,$collaborator);
                    }
                }
                
                exit(json_encode(["status"=>1,"message"=>"Research successfully added","url"=>"index.php"]));
            }
            // exit($password);
        }else{
            exit(json_encode(["status"=>0,"message"=>"Please fill important fields"]));
        }
    }

    if(isset($_POST['fetch_collaborators'])){
        $sample_name=$_POST['sample_name'];
        $collaborators=$research->fetch_collaborators($sample_name);
        exit(json_encode($collaborators));
    }

    if(isset($_POST['fetch_contributors'])){
        $research_id=$_POST['research_id'];
        fetch_contributors($research_id);
    }

    if(isset($_POST['fetch_comments'])){
        $research_id=$_POST['research_id'];
        fetch_comments($research_id);
        
    }
    
    if(isset($_POST['share_research'])){
        $research_id=$_POST['research_id'];
        if(isset($_POST['share_to_public'])){
            if(!$research->is_shared_to_public($research_id)){
                $message=$research->share_to_public($research_id);
            }else{
                exit(json_encode(["status"=>0,"message"=>"Research has alreday been shared to the public"]));
            }
        }elseif(isset($_POST['share_to_few'])){
            $researcher_id=$_POST['researcher_id'];
            $shared_to=$_POST['shared_to'];
            if(!$research->is_shared_to_this_other_researcher($research_id,$shared_to)){
                $message=$research->share_to_this_other_researcher($research_id,$researcher_id,$shared_to);
            }else{
                exit(json_encode(["status"=>0,"message"=>"Research has alreday been shared to this person"]));
            }
        }
        exit($message);
    }

    if(isset($_POST['seek_research_follow'])){
        $follower_id=$_POST['follower_id'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];

        if(!$researcher->is_following_research($research_id,$follower_id) && !$researcher->has_sent_follow_research_request($research_id,$follower_id)){
            $message=$researcher->send_request_to_follow_research($research_id,$researcher_id,$follower_id);
        }elseif($researcher->is_following_research($research_id,$follower_id)){
            exit(json_encode(["status"=>0,"message"=>"You are already following this research"]));
        }elseif($researcher->has_sent_follow_research_request($research_id,$follower_id)){
            exit(json_encode(["status"=>0,"message"=>"You have already sent request to follow research"]));
        }

        exit($message);
    }

    if(isset($_POST['allow_follow_research'])){
        $follower_id=$_POST['follower_id'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];

        if(!$research->is_following_research($research_id,$follower_id)){
            $message=$research->accept_request_to_follow_research($research_id,$researcher_id,$follower_id);
        }else{
            exit(json_encode(["status"=>0,"message"=>"This person is already following your research"]));
        }
        exit($message);

    }

    if(isset($_POST['add_research_comment'])){
        $comment_by=$_POST['comment_by'];
        $comment=$_POST['comment'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $striped_comment=preg_replace('/\s+/', '', $comment);
        if($researcher->is_authorized($research_id,$comment_by)){
            if($striped_comment!=""){
                $message=$research->comment($research_id,$researcher_id,$comment_by,$comment);
                if(json_decode($message,true)['status']==1){
                    fetch_comments($research_id);
                }
            }
        }else{
            exit("You are not authorized to add comment on research");
        }
    }

    if(isset($_POST['add_research_file'])){
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        if(isset($_FILES['research_file'])){
            if(is_uploaded_file($_FILES['research_file']['tmp_name'])){
                $file_temp = $_FILES['research_file']['tmp_name'];
                $file_name = $_FILES['research_file']['name'];
                $file_name_db=str_replace(" ","_",basename($file_name));
                $ext =  pathinfo($file_name,PATHINFO_EXTENSION);
                $ext_white_list=["jpg","png","PNG","docx","doc","mp4","mp3","pdf","ppt","pptx"];
                if(in_array($ext,$ext_white_list)){
                    if(!is_dir(SITE_PATH."user/assets/research_files/".$researcher_id."/".$research_id)){
                        mkdir(SITE_PATH."user/assets/research_files/".$researcher_id."/".$research_id,0777,true);
                    }
                    $file_path =  "user/assets/research_files/".$researcher_id."/".$research_id."/".date("Y-m-d-H-i-s-").$file_name;
                    $result =  move_uploaded_file($file_temp,SITE_PATH.$file_path);
                    if($result){
                        $file_name =  $file_name_db;
                        $result=$research->add_research_file($research_id,$researcher_id,$file_name,$file_path);
                        $file_id=json_decode($result,true)['file_id'];
                        add_research_file_reference($file_id);
                        $all_research_files=$research->get_all_research_files($research_id);
                        $total_research_files=count($all_research_files);
                        $t_r_f_msg="";
                        if($total_research_files>1){
                            $t_r_f_msg="$total_research_files files added";
                        }elseif($total_research_files==1){
                            $t_r_f_msg="$total_research_files files added";
                        }
                        $message = json_encode(array('status' => 1,'message'=>"File successfully added for research","total_research_files"=>$t_r_f_msg));
                        exit($message);
                    }else{
                        exit(json_encode(["status"=>0,"message"=>"Could not upload file"]));
                    }

                }else{
                    exit(json_encode(["status"=>0,"message"=>"Invalid file format"]));
                }
            }else{
                exit(json_encode(["status"=>0,"message"=>"Please! Upload a file"]));                
            }
        }else{
            exit(json_encode(["status"=>0,"message"=>"Select a file"]));
        }
    }

    if(isset($_POST['add_research_text'])){
        $text=$_POST['text'];
        $text_id=$_POST['text_id'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        if(!empty($text)){
            if($research->research_already_has_text($research_id)){
                $message=$research->update_research_text($text,$text_id,$research_id,$researcher_id);
            }else{
                $message=$research->add_research_text($research_id,$researcher_id,$text);
                $text_id=json_decode($message,true)['text_id'];
            }
            add_research_text_reference($text_id);

            exit($message);
        }else{
            exit(json_encode(["status"=>0,"message"=>"Please! Enter a write-up"]));
        }
    }

    if(isset($_POST['add_research_file_references'])){
        $file_id=$_POST['file_id'];
        $all_research_file_references=$research->get_all_research_file_references($file_id);

        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];

            if(!empty($all_research_file_references)){
                foreach ($all_research_file_references as $file_reference) {
                    if(!in_array($file_reference['reference_id'],$references_ids)){
                        $research->remove_research_file_reference($file_id,$file_reference['reference_id']);
                    }
                }
            }

            $i=0;
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_file_reference($file_id,$reference_id,$reference);
                $i++;                
            }
            exit($message);
        }
    }

    if(isset($_POST['add_research_text_references'])){
        $text_id=$_POST['text_id'];
        add_research_text_reference($text_id);

    }

    // Contribute research file
    if(isset($_POST['contribute_research_file'])){
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $contributor_id=$_POST['contributor_id'];
        if($researcher->is_authorized($research_id,$contributor_id)){
            if(isset($_FILES['research_contribution_file'])){
                if(is_uploaded_file($_FILES['research_contribution_file']['tmp_name'])){
                    $file_temp = $_FILES['research_contribution_file']['tmp_name'];
                    $file_name = $_FILES['research_contribution_file']['name'];
                    $file_name_db=str_replace(" ","_",basename($file_name));
                    $ext =  pathinfo($file_name,PATHINFO_EXTENSION);
                    $ext_white_list=["jpg","png","PNG","docx","doc","mp4","mp3","pdf","ppt","pptx"];
                    if(in_array($ext,$ext_white_list)){
                        if(!is_dir(SITE_PATH."user/assets/research_contributions_files/".$researcher_id."/".$research_id."/".$contributor_id)){
                            mkdir(SITE_PATH."user/assets/research_contributions_files/".$researcher_id."/".$research_id."/".$contributor_id,0777,true);
                        }
                        $file_path =  "user/assets/research_contributions_files/".$researcher_id."/".$research_id."/".$contributor_id."/".date("Y-m-d-H-i-s-").$file_name;
                        $result =  move_uploaded_file($file_temp,SITE_PATH.$file_path);
                        if($result){
                            $file_name =  $file_name_db;
                            $message=$research->contribute_file($research_id,$researcher_id,$contributor_id,$file_path,$file_name_db);
                            $file_id=json_decode($message,true)['file_id'];
                            add_research_contribution_file_reference($file_id);
                            fetch_contributors($research_id);
                        }else{
                            exit(json_encode(["status"=>0,"message"=>"Could not upload file"]));
                        }

                    }else{
                        exit(json_encode(["status"=>0,"message"=>"Invalid file format"]));
                    }
                }else{
                    exit(json_encode(["status"=>0,"message"=>"Please! Upload a file"]));                
                }
            }
        }else{
            exit("You are not authorized to contribute to research");
        }
    }

    if(isset($_POST['contribute_research_text'])){
        $research_contribution_text=$_POST['text'];
        $text_id=$_POST['text_id'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $contributor_id=$_POST['contributor_id'];
        if($researcher->is_authorized($research_id,$contributor_id)){
            if(!empty($research_contribution_text)){
                if($research->contributor_already_added_text($research_id,$contributor_id)){
                    $message=$research->update_research_contribution_text($research_contribution_text,$text_id,$research_id,$contributor_id);
                }else{
                    $message=$research->contribute_text($research_id,$researcher_id,$research_contribution_text,$contributor_id);
                    $text_id=json_decode($message,true)['text_id'];
                }
                add_research_contribution_text_reference($text_id);
                fetch_contributors($research_id);
            }else{
                exit("Please! Enter a write-up");
            }
        }else{
            exit("You are not authorized to contribute to research.");
        }
    }

    if(isset($_POST['add_research_contribution_file_references'])){
        $file_id=$_POST['file_id'];
        $research_contribution_file_references=$research->get_research_contribution_file_references($file_id);

        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];

            if(!empty($research_contribution_file_references)){
                foreach ($research_contribution_file_references as $contribution_file_reference) {
                    if(!in_array($contribution_file_reference['reference_id'],$references_ids)){
                        $research->remove_research_contribution_file_reference($file_id,$contribution_file_reference['reference_id']);
                    }
                }
            }

            $i=0;
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_file_reference($file_id,$reference_id,$reference);
                $i++;                
            }
            exit($message);
        }
    }

    if(isset($_POST['add_research_contribution_text_references'])){
        $text_id=$_POST['text_id'];
        $research_contribution_text_references=$research->get_research_contribution_text_references($text_id);

        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];

            if(!empty($research_contribution_text_references)){
                foreach ($research_contribution_text_references as $contribution_text_reference) {
                    if(!in_array($contribution_text_reference['reference_id'],$references_ids)){
                        $research->remove_research_contribution_text_reference($text_id,$contribution_text_reference['reference_id']);
                    }
                }
            }

            $i=0;
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_text_reference($text_id,$reference_id,$reference);
                $i++;                
            }
            exit($message);
        }
    }


    if(isset($_POST['get_contributor_files'])){
        $research_id=$_POST['research_id'];
        $contributor=$_POST['contributor'];
        $end=$_POST['end'];
        $contributor_files=$research->get_contributor_files($research_id,$contributor);
        $nextBtn="";
        $prevBtn="";
        if(!empty($contributor_files)){
            if($end>=count($contributor_files)){
                $end=count($contributor_files)-1;
                $prev=$end-1;
                $next=$end+1;
                $prevBtn="<button class='btn btn-danger' onclick='showContributorFilesModal($research_id,$contributor,$prev)'>Previous</button>";
            }elseif($end<0){
                $end=0;
                $prev=$end-1;
                $next=$end+1;
                $nextBtn="<button class='btn btn-primary' onclick='showContributorFilesModal($research_id,$contributor,$next)'>Next</button>";
            }else{
                $end=$end;
                $prev=$end-1;
                if($end==0){
                    $prev=$end;
                }
                $next=$end+1;
                if($end<count($contributor_files)-1){
                    $nextBtn="<button class='btn btn-primary' onclick='showContributorFilesModal($research_id,$contributor,$next)'>Next</button>";
                }
                if($end>0){
                    $prevBtn="<button class='btn btn-danger' onclick='showContributorFilesModal($research_id,$contributor,$prev)'>Previous</button>";

                }
            }
            $file_details=$contributor_files[$end];
            $file_path=$file_details['file_directory'];
            $file_name=$file_details['file_name'];
            $file_id=$file_details['file_id'];
            $file_name_exploded=explode(".",$file_name);
            $file_extension=$file_name_exploded[count($file_name_exploded)-1];

            $file_logos=["pdf"=>"../assets/img/file_logos/pdf.jpeg","doc"=>"../assets/img/file_logos/word.png","docx"=>"../assets/img/file_logos/word.png","txt"=>"../assets/img/file_logos/txt.jpeg","ppt"=>"../assets/img/file_logos/pp.jpeg","pptx"=>"../assets/img/file_logos/pp.jpeg"];
            $image_types=["jpg","jpeg","png","PNG","gif"];
            $video_types=["mp4"];
            $audio_types=["mp3"];

            

            $download_link="";
            if($researcher->is_authorized($research_id,$contributor)){
                $download_link="&nbsp; <a href='$file_path' download style='color:blue;font-size:18px;'> <i class='fa fa-download'></i></a>";
            }

            

            if(array_key_exists($file_extension,$file_logos)){
                $file_display="<img src='$file_logos[$file_extension]' class='img img-responsive' height='85px'>";
            }
            if(in_array($file_extension,$image_types)){
                $file_display="<img src='../$file_path' class='img img-responsive'>";
            }
            if(in_array($file_extension,$video_types)){
                $file_display="<video width='640' height='400' src='../$file_path' controls ></video>";
            }
            if(in_array($file_extension,$audio_types)){
                $file_display="<audio src='../$file_path'> </audio>";
            }

            echo "<div style='padding:25px;' class='mx-auto'>
            <center><div class='row' class='mx-auto'>
                <center>$file_display</center>
            </div>
            <div class='row'>
                $file_name $download_link
            </div></center>";

            $contribution_file_references=$research->get_research_contribution_file_references($file_id);

            echo "<div class='row'><div>References: </div>";
            if(!empty($contribution_file_references)){
                foreach ($contribution_file_references as $contribution_file_reference) {
                    $reference_id=$contribution_file_reference['reference_id'];
                    $contribution_file_reference_details=$research->reference_details($reference_id);
                    $contribution_reference=$contribution_file_reference_details['reference'];
                    echo $contribution_reference;
                }
            }else{
                echo "<div>No references</div>";
            }
            echo "</div>";

            echo "<div class='row' style='margin-top:25px'>
                <div class='col-md-5'>
                    $prevBtn
                </div>
                <div class='col-md-2'>
                </div>
                <div class='col-md-5'>
                    $nextBtn
                </div>
            </div>
            </div>
            ";
        }else{
            echo "No contribution files";
        }
    }

    if(isset($_POST['get_research_files'])){
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $end=$_POST['end'];
        $research_files=$research->get_all_research_files($research_id);
        $nextBtn="";
        $prevBtn="";
        if(!empty($research_files)){
            if($end>=count($research_files)){
                $end=count($research_files)-1;
                $prev=$end-1;
                $next=$end+1;
                $prevBtn="<button class='btn btn-danger' onclick='showResearchFilesModal($research_id,$researcher_id,$prev)'>Previous</button>";
            }elseif($end<0){
                $end=0;
                $prev=$end-1;
                $next=$end+1;
                $nextBtn="<button class='btn btn-primary' onclick='showResearchFilesModal($research_id,$researcher_id,$next)'>Next</button>";
            }else{
                $end=$end;
                $prev=$end-1;
                if($end==0){
                    $prev=$end;
                }
                $next=$end+1;
                if($end<count($research_files)-1){
                    $nextBtn="<button class='btn btn-primary' onclick='showResearchFilesModal($research_id,$researcher_id,$next)'>Next</button>";
                }
                if($end>0){
                    $prevBtn="<button class='btn btn-danger' onclick='showResearchFilesModal($research_id,$researcher_id,$prev)'>Previous</button>";

                }
            }
            $file_details=$research_files[$end];
            $file_path=$file_details['file_directory'];
            $file_name=$file_details['file_name'];
            $file_id=$file_details['file_id'];
            $file_name_exploded=explode(".",$file_name);
            $file_extension=$file_name_exploded[count($file_name_exploded)-1];

            $file_logos=["pdf"=>"../assets/img/file_logos/pdf.jpeg","doc"=>"../assets/img/file_logos/word.png","docx"=>"../assets/img/file_logos/word.png","txt"=>"../assets/img/file_logos/txt.jpeg","ppt"=>"../assets/img/file_logos/pp.png","pptx"=>"../assets/img/file_logos/pp.png"];
            $image_types=["jpg","jpeg","png","PNG","gif"];
            $video_types=["mp4"];
            $audio_types=["mp3"];

            

            $download_link="";
            if($researcher->is_authorized($research_id,$researcher_id)){
                $download_link="&nbsp; <a href='$file_path' download style='color:blue;font-size:18px;'> <i class='fa fa-download'></i></a>";
            }

            

            if(array_key_exists($file_extension,$file_logos)){
                $file_display="<img src='$file_logos[$file_extension]' class='img img-responsive' height='85px'>";
            }
            if(in_array($file_extension,$image_types)){
                $file_display="<img src='../$file_path' class='img img-responsive'>";
            }
            if(in_array($file_extension,$video_types)){
                $file_display="<video width='640' height='400' src='../$file_path' controls ></video>";
            }
            if(in_array($file_extension,$audio_types)){
                $file_display="<audio src='../$file_path'> </audio>";
            }

            echo "<div style='padding:25px;' class='mx-auto'>
            <center><div class='row' class='mx-auto'>
                <center>$file_display</center>
            </div>
            <div class='row'>
                $file_name $download_link
            </div></center>";

            $research_file_references=$research->get_all_research_file_references($file_id);

            echo "<div class='row'><div>References: </div>";
            if(!empty($research_file_references)){
                foreach ($research_file_references as $research_file_reference) {
                    $reference_id=$research_file_reference['reference_id'];
                    $research_file_reference_details=$research->reference_details($reference_id);
                    $research_reference=$research_file_reference_details['reference'];
                    echo $research_reference;
                }
            }else{
                echo "<div>No references</div>";
            }
            echo "</div>";

            echo "<div class='row' style='margin-top:25px'>
                <div class='col-md-5'>
                    $prevBtn
                </div>
                <div class='col-md-2'>
                </div>
                <div class='col-md-5'>
                    $nextBtn
                </div>
            </div>
            </div>
            ";
        }else{
            echo "No file has been added yet";
        }
    }

    if(isset($_POST['get_contributor_writeup'])){
        $research_id=$_POST['research_id'];
        $contributor=$_POST['contributor'];
        $contributor_text=$research->get_contributor_text($research_id,$contributor);
        if(!empty($contributor_text)){
            $contribution_text_content=$contributor_text['text'];
            $contribution_text_id=$contributor_text['text_id'];
            $contribution_text_references=$research->get_research_contribution_text_references($contribution_text_id);
            echo "<div class='row'>
                <div style='font-size:11px;'>$contribution_text_content</div>
            </div>
            ";
            echo "<div class='row'>References:";
            if(!empty($contribution_text_references)){
                foreach ($contribution_text_references as $contribution_text_reference) {
                    $reference_id=$contribution_text_reference['reference_id'];
                    $contribution_text_reference_details=$research->reference_details($reference_id);
                    $contribution_reference=$contribution_text_reference_details['reference'];
                    echo $contribution_reference;
                }
            }else{
                echo "<div>No references</div>";
            }
            echo "</div>";
        }else{
            echo "No write-up contributed";
        }
    }

    if(isset($_POST['report_research'])){
        $report=$_POST['report'];
        $reporter_id=$_POST['reporter_id'];
        $research_id=$_POST['research_id'];
        exit($research->report($research_id,$report,$reporter_id));
    }

    if(isset($_POST['report_researcher'])){
        $report=$_POST['report'];
        $reporter_id=$_POST['reporter_id'];
        $researcher_id=$_POST['researcher_id'];
        exit($researcher->report($researcher_id,$report,$reporter_id));
    }

    if(isset($_POST['remove_research'])){
        $research_id=$_POST['research_id'];
        exit($research->remove($research_id));
    }

    if(isset($_POST['decide_on_follow_request'])){
        $is_approved=$_POST['is_approved'];
        $research_id=$_POST['research_id'];
        $researcher_id=$_POST['researcher_id'];
        $follower_id=$_POST['follower_id'];
        $approval_div_id=$_POST['approval_div_id'];

        $research->decide_on_follow_request($is_approved,$research_id,$researcher_id,$follower_id);

        $approval_msg="";
        $message="";
        if($is_approved==1){
            $message="Follow request accepted";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:blue'>Approved</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-primary'". '  onclick="decideOnFollowRequest(2,'.$research_id.','.$follower_id.','."'$approval_div_id'".')"'.">Make Pending</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-danger'". '  onclick="decideOnFollowRequest(0,'.$research_id.','.$follower_id.','."'$approval_div_id'".')"'.">Decline</button>
                </div>
                
            ";
        }elseif($is_approved==0){
            $message="Follow request declined";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:red'>Declined</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-primary'". '  onclick="decideOnFollowRequest(2,'.$research_id.','.$follower_id.','."'$approval_div_id'".')"'.">Make Pending</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-success'". '  onclick="decideOnFollowRequest(1,'.$research_id.','.$follower_id.','."'$approval_div_id'".')"'.">Approve</button>
                </div>
            ";
        }elseif($is_approved==2){
            $message="Follow request placed on pending list";
            $approval_msg="
                <div class='col-md-4'>
                    <span style='font-size:10px;color:grey'>Not yet approved</span>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-danger'". '  onclick="decideOnFollowRequest(0,'.$research_id.','.$follower_id.','."'$approval_div_id'".')"'.">Decline</button>
                </div>
                <div class='col-md-4'>
                    <button class='btn btn-success'". '  onclick="decideOnFollowRequest(1,'.$research_id.','.$follower_id.','."'$approval_div_id'".')"'.">Approve</button>
                </div>
            ";
        }

        exit(json_encode(["status"=>1,"message"=>$message,"approval_details"=>$approval_msg]));

    }


    if(isset($_POST['fetch_research_details'])){
        $research_id=$_POST['research_id'];
        $research_details=$research->details($research_id);
        var_dump($research_details);
        $research_title=$research_details['research_title'];
        $research_description=$research_details['description'];
        $all_users=$master->fetch_all_users();
        $sel="";
        $research_collaborators="";
        if(!empty($all_users)){
            foreach ($all_users as $a_user) {
                $u_id=$a_user['user_id'];
                if($researcher->is_in_collaboration_on_research($research_id,$u_id)){
                    $sel="selected";
                }

                $researcher_name=$a_user['surname']." ".$a_user['other_names'];
                $research_collaborators.="<option value='$u_id'>".$researcher_name."</option>";
            }
        }

        exit(json_encode(["status"=>1,"research_title"=>$research_title,"research_description"=>$research_description,"research_collaborators"=>$research_collaborators]));
        

    }







    ////////////////////// PAGINATIONS /////////////////////////

    if(isset($_POST['fetch_more_user_researches'])){
        
    }

    if(isset($_POST['fetch_more_researches'])){
        
    }

    if(isset($_POST['fetch_more_contributors'])){
        
    }

    if(isset($_POST['fetch_more_comments'])){
        
    }

    if(isset($_POST['fetch_more_research_files'])){
        
    }

    if(isset($_POST['fetch_more_contributors_files'])){
        
    }



    function add_research_text_reference($text_id)
    {
        global $research;
        $all_research_text_references=$research->get_all_research_text_references($text_id);


        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];

            if(!empty($all_research_text_references)){
                foreach ($all_research_text_references as $text_reference) {
                    if(!in_array($text_reference['reference_id'],$references_ids)){
                        $research->remove_research_text_reference($text_id,$text_reference['reference_id']);
                    }
                }
            }

            $i=0;
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_text_reference($text_id,$reference_id,$reference);
                $i++;                
            }
        }
    }

    function add_research_file_reference($file_id)
    {
        global $research;
        $all_research_file_references=$research->get_all_research_file_references($file_id);


        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];

            

            $i=0;
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_file_reference($file_id,$reference_id,$reference);
                $i++;                
            }
        }
    }

    function add_research_contribution_text_reference($text_id)
    {
        global $research;
        $all_research_text_references=$research->get_research_contribution_text_references($text_id);

        // exit($text_id);

        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];

            if(!empty($all_research_text_references)){
                foreach ($all_research_text_references as $text_reference) {
                    if(!in_array($text_reference['reference_id'],$references_ids)){
                        $research->remove_research_contribution_text_reference($text_id,$text_reference['reference_id']);
                    }
                }
            }

            $i=0;
            // exit(var_dump($references_ids));
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_contribution_text_reference($text_id,$reference_id,$reference);
                $i++;                
            }
        }
    }

    
    function add_research_contribution_file_reference($file_id)
    {
        global $research;

        if(isset($_POST['references'])){
            $references=$_POST['references'];
            $references_ids=$_POST['references_ids'];
            $i=0;
            foreach ($references as $reference) {
                $reference_id=$references_ids[$i];
                $message=$research->add_research_contribution_file_reference($file_id,$reference_id,$reference);
                $i++;                
            }
        }
    }    

    function fetch_comments($research_id){
        global $research;
        $research_comments=$research->get_all_comments($research_id);
        if(!empty($research_comments)){
            $all_comments_made="";
            foreach ($research_comments as $comment) {
                $comment_by=$comment['comment_by'];
                $comment_content=$comment['comment'];
                $comment_by_details=$research->get_user_data($comment_by);

                $comment_by_name=$comment_by_details['surname']." ".$comment_by_details['other_names'];
                $comment_by_pix=$comment_by_details['profile_pix'];

                 $all_comments_made.="<div class='row'>
                 <div class='col-md-5'>
                 <img src='../$comment_by_pix' height='18px' class='img img-responsive'/>
                 <div style='font-size:11px'>$comment_by_name</div>
                 </div>

                 <div class='col-md-1'>
                 </div>

                 <div class='col-md-6'>
                 $comment_content
                 </div>
                 </div>";
            }
            $message=json_encode(["status"=>1,"all_comments_made"=>$all_comments_made,"total_comments"=>count($research_comments)]);
        }else{
            $message=json_encode(["status"=>0,"all_comments_made"=>"No comments"]);
        }
        exit($message);
    }

    function fetch_contributors($research_id)
    {
        global $research;
        $file_contributors=$research->get_all_research_file_contributors($research_id);
        $text_contributors=$research->get_all_research_text_contributors($research_id);
        $total_contributed_files=$research->get_total_num_contribution_files($research_id);
        $total_contributed_texts=$research->get_total_num_contribution_texts($research_id);
        $printed_ids=[];
        $contribution_content="";
        if(!empty($file_contributors)){
            foreach ($file_contributors as $file_contributor) {
                $contributor=$file_contributor['contributor'];
                $contributor_files=$research->get_contributor_files($research_id,$contributor);
                $total_contributor_files=count($contributor_files);
                $contributor_text=$research->get_contributor_text($research_id,$contributor);
                $total_contributor_text=0;
                if(!empty($contributor_text)){
                    $total_contributor_text=1;
                }
                array_push($printed_ids,$contributor);
                $contributor_details=$research->get_user_data($contributor);
                $contributor_name=$contributor_details['surname']." ".$contributor_details['other_names'];
                $contributor_pix=$contributor_details['profile_pix'];

                 $contribution_content.="<div class='row' style='margin-bottom:10px'>
                 <div class='col-md-3'>
                 <img src='../$contributor_pix' height='18px' class='img img-responsive'/>
                 <div style='font-size:11px;'>$contributor_name</div>
                 </div>
                 <div class='col-md-9'>
                  Contributed <span style='border-bottom:1px blue solid;color:blue;cursor:pointer;' onclick=showContributorFilesModal($research_id,$contributor,0) data-toggle='modal' data-target='#newModal'>$total_contributor_files files</span> and <span style='border-bottom:1px blue solid;color:blue;cursor:pointer;' onclick=showContributorWriteUpModal($research_id,$contributor)  data-toggle='modal' data-target='#newModal'>$total_contributor_text write-ups</span>;
                 </div>
                 </div>";
            }
        }

        if(!empty($text_contributors)){
            foreach ($text_contributors as $text_contributor) {
                $contributor=$text_contributor['contributor'];
                if(!in_array($contributor,$printed_ids)){
                    $contributor_files=$research->get_contributor_files($research_id,$contributor);
                    $total_contributor_files=count($contributor_files);
                    $contributor_text=$research->get_contributor_text($research_id,$contributor);
                    $total_contributor_text=0;
                    if(!empty($contributor_text)){
                        $total_contributor_text=1;
                    }

                    array_push($printed_ids,$contributor);

                    $contributor_details=$research->get_user_data($contributor);
                    $contributor_name=$contributor_details['surname']." ".$contributor_details['other_names'];
                    $contributor_pix=$contributor_details['profile_pix'];

                    $contribution_content.="<div class='row' style='margin-bottom:10px'>
                     <div class='col-md-3'>
                     <img src='../$contributor_pix' height='18px' class='img img-responsive'/>
                     <div style='font-size:11px;'>$contributor_name</div>
                     </div>
                     <div class='col-md-9'>
                      Contributed <span style='border-bottom:1px blue solid;color:blue;cursor:pointer;' onclick=showContributorFilesModal($research_id,$contributor,0) data-toggle='modal' data-target='#newModal'>$total_contributor_files files</span> and <span style='border-bottom:1px blue solid;color:blue;cursor:pointer;' data-toggle='modal' data-target='#newModal' onclick=showContributorWriteUpModal($research_id,$contributor)>$total_contributor_text write-ups</span>
                     </div>;
                     </div>";
                }
                
            }
        }

        $message=json_encode(["status"=>1,"message"=>"Successfully saved","total_contributions"=>$total_contributed_files+$total_contributed_texts,"contribution_content"=>$contribution_content]);

        if(empty($file_contributors) && empty($text_contributors)){
            $message=json_encode(["status"=>0,"message"=>"","total_contributions"=>0,"contribution_content"=>"No contribution has been made to this research"]);
        }

        exit($message);
    }
?>