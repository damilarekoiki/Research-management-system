<?php
class Researcher extends Master
{
    // protected $db_conn;
    // protected $lang;


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////setters///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   public  function __construct($db_conn,$lang)
    {       
        // $this->db_conn=$db_conn;
        // $this->lang=$lang;
        Master::__construct($db_conn,$lang);
    }

    public function send_request_to_follow_research($research_id,$researcher_id,$follower_id)
    {
        $data  = array("research_id"=>$research_id,"researcher_id"=>$researcher_id,"follower_id"=>$follower_id,"is_approved"=>2,"date_followed"=>date("Y-m-d H:i:s"));

        $table = "research_follow";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Request successfully sent"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"There was a problem sending request"));
        }
        return $message;
    }

    public function accept_request_to_follow_research($research_id,$researcher_id,$follower_id)
    {
        $data  = array("is_approved"=>1,"date_followed"=>date("Y-m-d H:i:s"));

        $table = "research_follow";
        $where=" where research_id='$research_id' AND researcher_id='$researcher_id' AND follower_id='$follower_id'";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Request successfully sent"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"There was a problem sending request"));
        }
        return $message;
        
    }

    public function decline_request_to_follow_research($research_id,$researcher_id,$follower_id)
    {
        $data  = array("is_approved"=>0,"date_followed"=>date("Y-m-d H:i:s"));

        $table = "research_follow";
        $res = $this->updateData($data,$table);
        $where=" where research_id='$research_id' AND researcher_id='$researcher_id' AND follower_id='$follower_id'";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Request successfully sent"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"There was a problem sending request"));
        }
        return $message;
        
    }

    public function add_as_research_follower($research_id,$researcher_id,$follower_id)
    {
        $data  = array("research_id"=>$research_id,"researcher_id"=>$researcher_id,"follower_id"=>$follower_id,"is_approved"=>1,"date_followed"=>date("Y-m-d H:i:s"));

        $table = "research_follow";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Request successfully sent"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"There was a problem sending request"));
        }
        return $message;
    }

    public function remove_as_research_follower($research_id,$researcher_id,$follower_id)
    {
        $data  = array("research_id"=>$research_id,"researcher_id"=>$researcher_id,"follower_id"=>$follower_id);

        $table = "research_follow";
        $stmt = $this->db->prepare("DELETE FROM research_follow WHERE research_id= :research_id AND researcher_id= :researcher_id AND follower_id= :follower_id");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Researcher successfully removed from following research"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Researcher could not be removed"));
        }
        return $message;
    }

    public function remove_as_research_collaborator($research_id,$collaborator)
    {
        $data  = array("research_id"=>$research_id,"collaborator"=>$collaborator);

        $table = "research_collaborators";
        $stmt = $this->db->prepare("DELETE FROM $table WHERE research_id= :research_id AND collaborator= :collaborator");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Researcher successfully removed as collaborator"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Researcher could not be removed"));
        }
        return $message;
    }

    public function is_in_collaboration_on_research($research_id,$collaborator)
    {
        $data = "*";
        $table = "research_collaborators";
        $where = " where research_id='$research_id' AND collaborator='$collaborator'";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return false;
        }else{
            return true;
        } 
    }

    public function is_following_research($research_id,$follower_id)
    {
        $data = "*";
        $table = "research_follow";
        $where = " where research_id='$research_id' AND follower_id='$follower_id' AND is_approved=1";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return false;
        }else{
            return true;
        } 
    }

    public function has_sent_follow_research_request($research_id,$follower_id)
    {
        $data = "*";
        $table = "research_follow";
        $where = " where research_id='$research_id' AND follower_id='$follower_id' AND is_approved=2";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return false;
        }else{
            return true;
        } 
    }

    public function is_authorized($research_id,$researcher_id)
    {
        $research=$research = new Research($this->db,$this->lang);
        $follows_research=$this->is_following_research($research_id,$researcher_id);
        $collaborates_on_research=$this->is_in_collaboration_on_research($research_id,$researcher_id);
        $research_is_shared_to_this_researcher=$research->is_shared_to_this_other_researcher($research_id,$researcher_id);
        $research_is_shared_to_public=$research->is_shared_to_public($research_id);
        $user_details=$this->get_user_data($researcher_id);
        $research_owner_id=$research->details($research_id)['researcher_id'];
        $user_role=$user_details['user_role'];

        if($follows_research || $collaborates_on_research || $research_is_shared_to_this_researcher || $user_role==1 || $researcher_id==$research_owner_id){
            return true;
        }else {
            return false;
        }
    }

    public function report($researcher_id,$report,$reporter_id)
    {
        $report_id=$this->generate_researcher_report_id();
        $data  = ["report_id"=>$report_id,"report"=>$report,"reporter_id"=>$reporter_id,"researcher_id"=>$researcher_id,"date_reported"=>date("Y-m-d H:i:s")];
        $table = "researcher_report";
        $res = $this->insertData($data,$table);
        if($res){
            $message=json_encode(["status"=>1,"message"=>"Report successfully sent"]);
        }else{
            $message=json_encode(["status"=>0]);
        }
        return $message;
    }

    public function generate_researcher_report_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_researcher_report_id();
        return intval($id) + 1;
    }
    public function get_last_researcher_report_id(){
        $data = array("report_id");
        $table = "researcher_report";
        $where = " ORDER BY id DESC";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['report_id'];
        } 
    }



     

}