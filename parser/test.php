<?php
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
                            mkdir(SITE_PATH."user/assets/research_files/".$researcher_id."/".$research_id."/".$contributor_id,0777,true);
                        }
                        $file_path =  "user/assets/research_files/".$researcher_id."/".$research_id."/".$contributor_id."/".date("Y-m-d-H-i-s-").$file_name;
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




    
?>