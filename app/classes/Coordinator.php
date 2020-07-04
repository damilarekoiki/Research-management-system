<?php
class Coordinator extends Master
{
    // protected $user_role;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////setters///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   public  function __construct($db_conn,$lang)
    {       Master::__construct($db_conn,$lang);
        // $this->user_role = $user_role;
          
   }

   public function approve_research($research_id)
   {
        $data = array("is_approved"=>"1");
        $table = "research";
        $where = "WHERE research_id = '$research_id'";

        $check = $this->updateData($data, $table, $where);

        if($check){
            $message = json_encode(array('status' => 1,"message"=>"Research successfully approved"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"There was a problem approving report"));
        }
        return $message;

   }
   public function disapprove_research($research_id)
   {
    $data = array("is_approved"=>"0");
    $table = "research";
    $where = "WHERE research_id = '$research_id'";

    $check = $this->updateData($data, $table, $where);

    if($check){
        $message = json_encode(array('status' => 1,"message"=>"Research disapproved"));
    
    }else{
        $message = json_encode(array('status' => 0, "message"=>"There was a problem approving report"));
    }
    return $message;

   }
   public function report_research($research_id,$researcher_id,$coordinator_id,$report)
   {
    $data  = array("research_id"=>$research_id,"researcher_id"=>$researcher_id,"coordinator_id"=>$coordinator_id,"report"=>$report);

    $table = "research_report";
    $res = $this->insertData($data,$table);
    if($res){
        $message = json_encode(array('status' => 1,"message"=>"Report successfully sent"));
    
    }else{
        $message = json_encode(array('status' => 0, "message"=>"There was a problem sending report"));
    }
    return $message;

   }

   public function report_researcher($researcher_id,$coordinator_id)
   {
    $data  = array("researcher_id"=>$researcher_id,"coordinator_id"=>$coordinator_id,"report"=>$report);

    $table = "research_report";
    $res = $this->insertData($data,$table);
    if($res){
        $message = json_encode(array('status' => 1,"message"=>"Report successfully sent"));
    
    }else{
        $message = json_encode(array('status' => 0, "message"=>"There was a problem sending report"));
    }
    return $message;
   }

   public function decide_on_research_approval($is_approved,$research_id,$researcher_id)
    {
        $data  = ["is_approved"=>$is_approved];

        $table = "research";
        $where=" WHERE research_id=$research_id AND researcher_id=$researcher_id";

        $check = $this->updateData($data,$table,$where);
    }

    public function fetch_all()
    {
        $data = "*";
        $table = "user";
        $where = " WHERE user_role=1 ORDER BY id DESC";

        $result = $this->getAllData($data, $table, $where);
        return $result;
    }

}