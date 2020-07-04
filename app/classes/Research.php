<?php
class Research extends Master
{
    // protected $user_role;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////setters///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   public  function __construct($db_conn,$lang)
{       Master::__construct($db_conn,$lang);
        // $this->user_role = $user_role;
          
   }

   public function research_exists($research_title,$researcher_id)
   {
       # code...
       $data = "*";
        $table = "research";
        $where = " where research_title = '$research_title' AND researcher_id='$researcher_id'";

        $check = $this->getAllData($data, $table, $where);
        if(empty($check)){
            return false;
        }else{
            return true;
        } 

   }

    public function add($title,$description,$researcher_id)
    {
        $research_id = $this->generate_research_id();
        $data  = array("research_id"=>$research_id,"research_title"=>$title,"research_description"=>$description,"researcher_id"=>$researcher_id,"is_approved"=>2,"date_created"=>date("Y-m-d H:i:s"));

        $table = "research";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1, "research_id"=>$research_id,"message"=>"Research added"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not add research"));
        }
        return $message;
    }

    public function remove($research_id)
    {
        $data  = array("research_id"=>$research_id);

        $all_files_contributed=$this->get_all_contribution_files($research_id);
        if(!empty($all_files_contributed)){
            foreach ($all_files_contributed as $file_contributed) {
                $file_id=$file_contributed['file_id'];
                $stmt = $this->db->prepare("DELETE FROM research_contribution_files_references WHERE file_id= :file_id");
                $stmt->execute(["file_id"=>$file_id]);
            }
        }

        $all_text_contributed=$this->get_all_contribution_texts($research_id);
        if(!empty($all_text_contributed)){
            foreach ($all_text_contributed as $text_contributed) {
                $text_id=$text_contributed['text_id'];
                $stmt = $this->db->prepare("DELETE FROM research_contribution_text_references WHERE text_id= :text_id");
                $stmt->execute(["text_id"=>$text_id]);
            }
        }

        $all_research_files=$this->get_all_research_files($research_id);
        if(!empty($all_research_files)){
            foreach ($all_research_files as $research_file) {
                $file_id=$research_file['file_id'];
                $stmt = $this->db->prepare("DELETE FROM research_files_references WHERE file_id= :file_id");
                $stmt->execute(["file_id"=>$file_id]);
            }
        }

        $research_text=$this->get_research_text($research_id);
        if(!empty($research_text)){
            $text_id=$research_text['text_id'];
            $stmt = $this->db->prepare("DELETE FROM research_text_references WHERE text_id= :text_id");
            $stmt->execute(["text_id"=>$text_id]);
        }

        $stmt = $this->db->prepare("DELETE FROM research WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_collaborators WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_comments WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_contribution_files WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_contribution_text WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_files WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_follow WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_report WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_share WHERE research_id= :research_id");
        $stmt->execute($data);
        $stmt = $this->db->prepare("DELETE FROM research_text WHERE research_id= :research_id");


        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Research successfully deleted"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Research could not be deleted"));
        }
        return $message;
    }

    public function add_collaborator($research_id,$collaborator){
        $data  = array("research_id"=>$research_id,"collaborator"=>$collaborator);

        $table = "research_collaborators";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1));
        
        }else{
            $message = json_encode(array('status' => 0));
        }
        return $message;
    }

    public function fetch_all_collaborators($research_id){
        $data  = "*";
        $table = "research_collaborators";
        $where=" WHERE research_id=$research_id";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function get_total_collaborators($research_id){
        return count($this->fetch_all_collaborators($research_id));
    }

    public function get_all_researches()
    {
        $data  = "*";
        $table = "research";
        $where="ORDER BY id DESC";
        $res = $this->getAllData($data,$table,$where);
        
        return $res;
    }

    public function get_all_new_researches()
    {
        $data  = "*";
        $table = "research";
        $where="WHERE is_seen=0 ORDER BY id DESC";
        $res = $this->getAllData($data,$table,$where);
        
        return $res;
    }

    public function get_research_text($research_id)
    {
        $data  = "*";
        $table = "research_text";
        $where=" where research_id=$research_id";
        $res = $this->getData($data,$table,$where);
        
        return $res;
    }

    public function get_all_contribution_files($research_id)
    {
        $data  = "*";
        $table = "research_contribution_files";
        $where=" where research_id=$research_id";
        $res = $this->getAllData($data,$table,$where);
        
        return $res;
    }

    

    public function add_references_available($reference){
        
        if(!$this->reference_avail_exist($reference)){
            $reference_id=$this->generate_reference_id();
            $data  = array("reference"=>$reference,'reference_id'=>$reference_id);
            $table = "references_available";
            $res = $this->insertData($data,$table);
            if($res){
                $message = json_encode(array('status' => 1,'reference_id'=>$reference_id));
            
            }else{
                $message = json_encode(array('status' => 0));
            }
        }else{
            $reference_id=$this->get_reference_id($reference);
            $message = json_encode(array('status' =>1,'reference_id'=>$reference_id));
        }

        
        return $message;        
    }
    public function get_reference_id($reference)
    {
        $data = array("reference_id");
        $table = "references_available";
        $where = " where reference = '$reference' ";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['reference_id'];
        } 
    }

    public function fetch_collaborators($like_name){
        $data = "*";
        $table = "user";
        $where = " where surname like '%$like_name' or other_names like '%$like_name'";

        $result = $this->getAllData($data, $table, $where);
        return $result;
    }
    public function get_all_collaborators(){
        $data = "*";
        $table = "user";
        $where = "";

        $result = $this->getAllData($data, $table, $where);
        return $result;
    }
    public function get_all_references_avail(){
        $data = "*";
        $table = "references_available";
        $where = "";

        $result = $this->getAllData($data, $table, $where);
        return $result;
    }

    public function reference_avail_exist($reference)
    {
        $data = "*";
        $table = "references_available";
        $where = "WHERE reference = '$reference'  ";
        $check = $this->getData($data, $table, $where);
        $res = $this->getAllData($data,$table, $where);
        if(count($res)>0){
            return true;
        
        }else{
            return false;
        }
    }

   public function generate_research_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_research_id();
        return intval($id) + 1;
    }
    public function get_last_research_id(){
        $data = array("research_id");
        $table = "research";
        $where = " ORDER BY id DESC";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['research_id'];
        } 
    }

    public function generate_reference_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_reference_id();
        return intval($id) + 1;
    }
    public function get_last_reference_id(){
        $data = array("reference_id");
        $table = "references_available";
        $where = " ORDER BY id DESC";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['reference_id'];
        } 
    }

    public function add_research_file($research_id,$researcher_id,$file_name,$file_directory){
        $file_id=$this->generate_research_file_id();
        $data  = array('research_id'=>$research_id,"researcher_id"=>$researcher_id,"file_name"=>$file_name,"file_directory"=>$file_directory,"file_id"=>$file_id,"date_uploaded"=>date("Y-m-d H:i:s"));
        $table = "research_files";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,'message'=>"File successfully added for research","file_id"=>$file_id));
        
        }else{
            $message = json_encode(array('status' => 0,'message'=>"Could not add file for research"));
        }

        
        return $message;   
    }


    public function generate_research_file_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_research_file_id();
        return intval($id) + 1;
    }
    public function get_last_research_file_id(){
        $data = array("file_id");
        $table = "research_files";
        $where = " ORDER BY id DESC";
        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['file_id'];
        } 
    }


    //////////////////////////// New methods//////////////////////////////////////////

    public function add_research_text($research_id,$researcher_id,$text){
        $text_id=$this->generate_research_text_id();
        $data  = array('research_id'=>$research_id,"researcher_id"=>$researcher_id,"text"=>$text,"text_id"=>$text_id,"date_added"=>date("Y-m-d H:i:s"));
        $table = "research_text";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,'message'=>"Write-up successfully saved for research","text_id"=>$text_id));
        
        }else{
            $message = json_encode(array('status' => 0,'message'=>"Could not save wtite-up for research"));
        }

        
        return $message;   
    }

    public function generate_research_text_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_research_text_id();
        return intval($id) + 1;
    }
    public function get_last_research_text_id(){
        $data = array("text_id");
        $table = "research_text";
        $where = " ORDER BY id DESC";
        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['text_id'];
        } 
    }

    public function generate_research_contribution_text_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_research_contribution_text_id();
        return intval($id) + 1;
    }
    public function get_last_research_contribution_text_id(){
        $data = array("text_id");
        $table = "research_contribution_text";
        $where = " ORDER BY id DESC";
        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['text_id'];
        } 
    }

    public function generate_research_contribution_file_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_research_contribution_file_id();
        return intval($id) + 1;
    }
    public function get_last_research_contribution_file_id(){
        $data = array("file_id");
        $table = "research_contribution_files";
        $where = " ORDER BY id DESC";
        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['file_id'];
        } 
    }

    public function update_research_text($text,$text_id,$research_id,$researcher_id){
        $data = array("text"=>$text,"researcher_id"=>$researcher_id);
        $table = "research_text";
        $where = " WHERE research_id=$research_id AND text_id=$text_id";

        $res = $this->updateData($data, $table, $where);
        if($res){
            $message = json_encode(array('status' => 1,'message'=>"Saved"));
        
        }else{
            $message = json_encode(array('status' => 0,'message'=>"Could not save"));
        }

        return $message;
    }

    public function update_research_contribution_text($text,$text_id,$research_id,$contributor_id){
        $data = array("text"=>$text);
        $table = "research_contribution_text";
        $where = " WHERE research_id=$research_id AND text_id=$text_id AND contributor=$contributor_id";

        $res = $this->updateData($data, $table, $where);
        if($res){
            $message = json_encode(array('status' => 1,'message'=>"Saved"));
        
        }else{
            $message = json_encode(array('status' => 0,'message'=>"Could not save"));
        }

        return $message;
    }

    public function research_already_has_text($research_id){
        $data = "*";
        $table = "research_text";
        $where = " WHERE research_id=$research_id";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return false;
        
        }else{
            return true;
        }
    }

    public function remove_research_text($research_id){
        $data  = array("research_id"=>$research_id);

        $stmt = $this->db->prepare("DELETE FROM research_text WHERE research_id= :research_id");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Write-up successfully deleted"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Write-up could not be deleted"));
        }
        return $message;
    }

    public function remove_research_file($research_id,$file_id){
        $data  = array("research_id"=>$research_id,"file_id"=>$file_id);

        $stmt = $this->db->prepare("DELETE FROM research_files WHERE research_id= :research_id AND file_id=:file_id ");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"File successfully deleted"));
        }else{
            $message = json_encode(array('status' => 0, "message"=>"File could not be deleted"));
        }
        return $message;
    }

    public function contribute_file($research_id,$researcher,$contributor,$file_directory,$file_name)
    {
        $file_id=$this->generate_research_contribution_file_id();
        $data  = array("research_id"=>$research_id,"researcher"=>$researcher,"file_id"=>$file_id,"file_directory"=>$file_directory,"file_name"=>$file_name,"contributor"=>$contributor,"date_uploaded"=>date("Y-m-d H:i:s"));

        $table = "research_contribution_files";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"File uploaded successfully","file_id"=>$file_id));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not upload file"));
        }
        return $message;
    }

    public function contribute_text($research_id,$researcher_id,$text,$contributor)
    {
        $text_id=$this->generate_research_contribution_text_id();
        $data  = array("research_id"=>$research_id,"researcher"=>$researcher_id,"text"=>$text,"contributor"=>$contributor,"text_id"=>$text_id,"date_added"=>date("Y-m-d H:i:s"));

        $table = "research_contribution_text";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Saved","text_id"=>$text_id));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not save"));
        }
        return $message;
    }

    public function comment($research_id,$researcher_id,$comment_by,$comment)
    {
        $data  = array("research_id"=>$research_id,"researcher_id"=>$researcher_id,"comment_by"=>$comment_by,"comment"=>$comment,"date_added"=>date("Y-m-d H:i:s"));

        $table = "research_comments";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Comment added successfully"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not add comment"));
        }
        return $message;
    }

    public function researcher_has_commented($research_id,$comment_by)
    {
        $data  = "*";
        $where=" where research_id=$research_id AND comment_by=$comment_by";
        $table = "research_comments";
        $res = $this->getAllData($data,$table,$where);
        if(empty($res)){
            return false;        
        }else{
            return true;        
        }
    }

    public function researcher_has_contributed($research_id,$contributor)
    {
        $data  = "*";
        $where=" where research_id=$research_id AND contributor=$contributor";
        $table1 = "research_contribution_files";
        $res1 = $this->getAllData($data,$table1,$where);
        $table2 = "research_contribution_text";
        $res2 = $this->getAllData($data,$table2,$where);
        if(!empty($res1) || !empty($res2)){
            return true;        
        }else{
            return false;        
        }
    }

    public function get_all_contribution_texts($research_id){
        $data = "*";
        $table = "research_contribution_text";
        $where = " WHERE research_id=$research_id";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function get_contributor_text($research_id,$contributor){
        $data = "*";
        $table = "research_contribution_text";
        $where = " WHERE research_id=$research_id AND contributor='$contributor'";

        $check = $this->getData($data, $table, $where);
        return $check;
    }

    public function contributor_already_added_text($research_id,$contributor_id){
        if(empty($this->get_contributor_text($research_id,$contributor_id))){
            return false;
        }else{
            return true;
        }
    }

    public function get_contributor_files($research_id,$contributor){
        $data = "*";
        $table = "research_contribution_files";
        $where = " WHERE research_id=$research_id AND contributor='$contributor'";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function get_all_approved_researches(){
        $data = "*";
        $table = "research";
        $where = " WHERE is_approved=1 ORDER BY id DESC";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function get_all_disapproved_researches(){
        $data = "*";
        $table = "research";
        $where = " WHERE is_approved=0";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function get_all_researcher_approved_researches($researcher_id){
        $data = "*";
        $table = "research";
        $where = " WHERE is_approved=1 AND researcher_id='$researcher_id'";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function get_all_researcher_disapproved_researches($researcher_id){
        $data = "*";
        $table = "research";
        $where = " WHERE is_approved=0 AND researcher_id='$researcher_id'";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function get_all_researcher_pending_researches($researcher_id){
        $data = "*";
        $table = "research";
        $where = " WHERE is_approved=0 AND researcher_id='$researcher_id'";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function search($research_title){
        $data = "*";
        $table = "research";
        $where = " WHERE research_title LIKE '%$research_title%'";

        $check = $this->getAllData($data, $table, $where);
        return $check;
    }

    public function share_to_public($research_id){
        $data = array("is_shared_to_public"=>1);
        $table = "research";
        $where = " WHERE research_id = $research_id";

        $res = $this->updateData($data, $table, $where);
        if($res){
            return json_encode(["status"=>1,"message"=>"Your research has been shared to the public"]);
        }else{
            return json_encode(["status"=>1,"message"=>"Could not share to the public"]);

        }
    }

    public function share_to_this_other_researcher($research_id,$researcher_id,$shared_to){
        $data = array("research_id"=>$research_id,"researcher_id"=>$researcher_id,"shared_to"=>$shared_to,"date_shared"=>date("Y:m:d H:i:s"));
        $table = "research_share";

        $res = $this->insertData($data, $table);
        if($res){
            return json_encode(["status"=>1,"message"=>"Shared successfully"]);
        }else{
            return json_encode(["status"=>1,"message"=>"Could not share the research"]);

        }
    }

    public function is_shared_to_public($research_id){
        $data = "*";
        $table = "research";
        $where=" WHERE is_shared_to_public=1 AND research_id=$research_id";

        $check = $this->getData($data, $table,$where);
        if(empty($check)){
            return false;
        }else{
            return true;
        }
    }

    public function is_shared_to_this_other_researcher($research_id,$this_other_researcher_id){
        $data = "*";
        $table = "research_share";
        $where=" WHERE research_id=$research_id AND shared_to=$this_other_researcher_id";

        $check = $this->getData($data, $table,$where);
        if(empty($check)){
            return false;
        }else{
            return true;
        }
    }

    

    public function get_all_comments($research_id){
        $data = "*";
        $table = "research_comments";
        $where=" WHERE research_id=$research_id ORDER BY id DESC";

        $check = $this->getAllData($data, $table,$where);
        return $check;
    }

    public function get_total_num_comments($research_id){
        return count($this->get_all_comments($research_id));
    }

    public function get_total_num_contribution_files($research_id){
        return count($this->get_all_contribution_files($research_id));
    }

    public function get_total_num_contribution_texts($research_id){
        return count($this->get_all_contribution_texts($research_id));        
    }

    public function get_total_num_contributors($research_id){
        // use sql distinct syntax here
        $data  = array("research_id"=>$research_id);

        $stmt1 = $this->db->prepare("SELECT DISTINCT contributor FROM research_contribution_files WHERE research_id= :research_id");
        $stmt1->execute($data);
        $result1=$stmt1->fetchAll();

        $stmt2 = $this->db->prepare("SELECT DISTINCT contributor FROM research_contribution_text WHERE research_id= :research_id");
        $stmt2->execute($data);
        $result2=$stmt2->fetchAll();

        $total_contributors= count($result1) + count($result2);
        return $total_contributors;
    }

    public function get_total_num_followers($research_id){
        // use sql distinct syntax here
        $data  = array("research_id"=>$research_id);

        $stmt = $this->db->prepare("SELECT DISTINCT follower_id FROM research_follow WHERE research_id= :research_id AND is_approved=1");
        $stmt->execute($data);
        $result=$stmt->fetchAll();

        $total_followers= count($result);
        return $total_followers;
    }

    public function get_all_new_follow_requests($researcher_id)
    {
        $data  = "*";

        $table = "research_follow";
        $where=" WHERE researcher_id=$researcher_id AND is_seen=0";

        $check = $this->getAllData($data,$table,$where);
        return $check;
    }

    public function get_all_follow_requests($researcher_id)
    {
        $data  = "*";

        $table = "research_follow";
        $where=" WHERE researcher_id=$researcher_id";

        $check = $this->getAllData($data,$table,$where);
        return $check;
    }

    public function follow_request_is_new($research_id,$researcher_id,$follower_id)
    {
        $data  = "*";

        $table = "research_follow";
        $where=" WHERE researcher_id=$researcher_id AND research_id=$research_id AND follower_id=$follower_id AND is_seen=0";

        $check = $this->getAllData($data,$table,$where);
        if(empty($check)){
            return false;
        }else{
            return true;
        }
    }

    public function get_total_new_follow_requests($researcher_id)
    {
        return count($this->get_all_new_follow_requests($researcher_id));
    }

    public function update_all_follow_requests_is_seen($researcher_id)
    {
        $data  = ["is_seen"=>0];

        $table = "research_follow";
        $where=" WHERE researcher_id=$researcher_id";

        $check = $this->updateData($data,$table,$where);
    }

    public function update_follow_request_is_seen($research_id,$follower_id)
    {
        $data  = ["is_seen"=>1];

        $table = "research_follow";
        $where=" WHERE research_id=$research_id AND follower_id=$follower_id";

        $check = $this->updateData($data,$table,$where);
    }

    public function update_research_creation_is_seen($research_id)
    {
        $data  = ["is_seen"=>1];

        $table = "research";
        $where=" WHERE research_id=$research_id";

        $check = $this->updateData($data,$table,$where);
    }

    public function decide_on_follow_request($is_approved,$research_id,$researcher_id,$follower_id)
    {
        $data  = ["is_approved"=>$is_approved];

        $table = "research_follow";
        $where=" WHERE research_id=$research_id AND researcher_id=$researcher_id AND follower_id=$follower_id";

        $check = $this->updateData($data,$table,$where);
    }

    public function get_all_research_file_contributors($research_id){
        // use sql distinct syntax here
        $data  = array("research_id"=>$research_id);

        $stmt = $this->db->prepare("SELECT DISTINCT contributor FROM research_contribution_files WHERE research_id= :research_id ORDER BY id DESC");
        $stmt->execute($data);
        $result=$stmt->fetchAll();

        return $result;
    }

    public function get_all_research_text_contributors($research_id){
        // use sql distinct syntax here
        $data  = array("research_id"=>$research_id);

        $stmt = $this->db->prepare("SELECT DISTINCT contributor FROM research_contribution_text WHERE research_id= :research_id ORDER BY id DESC");
        $stmt->execute($data);
        $result=$stmt->fetchAll();

        return $result;
    }

    public function details($research_id){
        $data = "*";
        $table = "research";
        $where=" WHERE research_id=$research_id";

        $check = $this->getData($data, $table,$where);
        return $check;
    }


///////////////// Pending /////////////////

    public function download(){
        
    }


    public function add_research_file_reference($file_id,$reference_id,$reference)
    {
        
        if($reference_id=="undefined"){
            $result=json_decode($this->add_references_available($reference),true);
            if($result['status']==1){
                $reference_id=$result['reference_id'];
            }
        }
        $data  = array("file_id"=>$file_id,"reference_id"=>$reference_id);

        $table = "research_files_references";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1));
        
        }else{
            $message = json_encode(array('status' => 0));
        }
        return $message;
    }

    public function add_research_text_reference($text_id,$reference_id,$reference)
    {
        $message = json_encode(array('status' => 1,"message"=>"Saved"));
        if($reference_id=="undefined"){
            $result=json_decode($this->add_references_available($reference),true);
            if($result['status']==1){
                $reference_id=$result['reference_id'];
                $message = json_encode(array('status' => 1,"message"=>"Saved"));
            }else{
                $message = json_encode(array('status' => 0,"message"=>"Could not save"));
            }
        }

        if(!$this->research_text_owns_reference($text_id,$reference_id)){
            $data  = array("text_id"=>$text_id,"reference_id"=>$reference_id,"date_added"=>date("Y-m-d H:i:s"));

            $table = "research_text_references";
            $res = $this->insertData($data,$table);
            if($res){
                $message = json_encode(array('status' => 1,"message"=>"Saved"));
            
            }else{
                $message = json_encode(array('status' => 0,"message"=>"Could not save"));
            }
            return $message;
        }
        
    }

    public function research_text_owns_reference($text_id,$reference_id)
    {
        $data  = "*";

        $table = "research_text_references";
        $where=" WHERE text_id='$text_id' AND reference_id=$reference_id";
        $res = $this->getData($data,$table,$where);
        if(empty($res)){
            return false;
        
        }else{
            return true;

        }
        
    }

    public function research_contribution_text_owns_reference($text_id,$reference_id)
    {
        $data  = "*";

        $table = "research_contribution_text_references";
        $where=" WHERE text_id=$text_id AND reference_id=$reference_id";
        $res = $this->getData($data,$table,$where);
        if(empty($res)){
            return false;
        
        }else{
            return true;

        }
        
    }

    public function edit_research_file_reference($file_id,$reference_id)
    {
        
        $data  = array("reference_id"=>$reference_id);

        $table = "research_files_references";
        $where=" where file_id='$file_id' AND reference_id=$old_ref_id";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Reference updated successfully"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not update reference"));
        }
        return $message;
    }

    public function edit_research_text_reference($text_id,$reference_id,$old_ref_id)
    {
        
        $data  = array("reference_id"=>$reference_id);

        $table = "research_text_references";
        $where=" where text_id='$text_id' AND reference_id=$old_ref_id";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Reference updated successfully"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not update reference"));
        }
        return $message;
    }

    public function edit($research_id,$title,$description)
    {
        
        $data  = array("research_id"=>$research_id,"research_title"=>$title,"research_description"=>$description);

        $table = "research";
        $where=" where research_id='$research_id'";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Research successfully edit"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not update reference"));
        }
        return $message;
    }

    public function remove_research_file_reference($file_id,$reference_id)
    {
        
        $data  = array("file_id"=>$file_id,"reference_id"=>$reference_id);
        $stmt = $this->db->prepare("DELETE FROM research_files_references WHERE file_id= :file_id AND reference_id= :reference_id");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Reference successfully removed"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not remove reference"));
        }
        return $message;
    }

    public function remove_research_text_reference($text_id,$reference_id)
    {
        $data  = array("text_id"=>$text_id,"reference_id"=>$reference_id);
        $stmt = $this->db->prepare("DELETE FROM research_text_references WHERE text_id= :text_id AND reference_id= :reference_id");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Reference successfully removed"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not remove reference"));
        }
        return $message;
    }

    public function get_all_research_file_references($file_id)
    {
        $data  = "*";
        $table = "research_files_references";
        $where=" where file_id='$file_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function get_all_research_files($research_id)
    {
        $data  = "*";
        $table = "research_files";
        $where=" where research_id='$research_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function get_all_research_text_references($text_id)
    {
        $data  = "*";
        $table = "research_text_references";
        $where=" where text_id='$text_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }


    public function get_research_contribution_file_references($file_id)
    {
        $data  = "*";
        $table = "research_contribution_files_references";
        $where=" where file_id='$file_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function get_research_contribution_text_references($text_id)
    {
        $data  = "*";
        $table = "research_contribution_text_references";
        $where=" where text_id='$text_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function reference_details($reference_id)
    {
        $data  = "*";
        $table = "references_available";
        $where=" where reference_id='$reference_id'";
        $res = $this->getData($data,$table,$where);
        return $res;
    }












    public function add_research_contribution_file_reference($file_id,$reference_id,$reference)
    {
        
        if($reference_id=="undefined"){
            $result=json_decode($this->add_references_available($reference),true);
            if($result['status']==1){
                $reference_id=$result['reference_id'];
            }
        }
        $data  = array("file_id"=>$file_id,"reference_id"=>$reference_id);

        $table = "research_contribution_files_references";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1));
        
        }else{
            $message = json_encode(array('status' => 0));
        }
        return $message;
    }

    public function add_research_contribution_text_reference($text_id,$reference_id,$reference)
    {
        
        if($reference_id=="undefined"){
            $result=json_decode($this->add_references_available($reference),true);
            if($result['status']==1){
                $reference_id=$result['reference_id'];
            }
        }
        $data  = array("text_id"=>$text_id,"reference_id"=>$reference_id);

        $table = "research_contribution_text_references";
        $res = $this->insertData($data,$table);
        if($res){
            $message = json_encode(array('status' => 1));
        
        }else{
            $message = json_encode(array('status' => 0));
        }
        return $message;
    }

    public function edit_research_contribution_file_reference($file_id,$reference_id)
    {
        
        $data  = array("reference_id"=>$reference_id);

        $table = "research_contribution_files_references";
        $where=" where file_id='$file_id' AND reference_id=$old_ref_id";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Reference updated successfully"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not update reference"));
        }
        return $message;
    }

    public function edit_research_contribution_text_reference($text_id,$reference_id,$old_ref_id)
    {
        
        $data  = array("reference_id"=>$reference_id);

        $table = "research_contribution_text_references";
        $where=" where text_id='$text_id' AND reference_id=$old_ref_id";
        $res = $this->updateData($data,$table,$where);
        if($res){
            $message = json_encode(array('status' => 1,"message"=>"Reference updated successfully"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not update reference"));
        }
        return $message;
    }

    public function remove_research_contribution_file_reference($file_id,$reference_id)
    {
        
        $data  = array("file_id"=>$file_id,"reference_id"=>$reference_id);
        $stmt = $this->db->prepare("DELETE FROM research_contribution_files_references WHERE file_id= :file_id AND reference_id= :reference_id");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Reference successfully removed"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not remove reference"));
        }
        return $message;
    }

    public function remove_research_contribution_text_reference($text_id,$reference_id)
    {
        $data  = array("text_id"=>$text_id,"reference_id"=>$reference_id);
        $stmt = $this->db->prepare("DELETE FROM research_contribution_text_references WHERE text_id= :text_id AND reference_id= :reference_id");
        if($stmt->execute($data)){
            $message = json_encode(array('status' => 1,"message"=>"Reference successfully removed"));
        
        }else{
            $message = json_encode(array('status' => 0, "message"=>"Could not remove reference"));
        }
        return $message;
    }

    public function get_all_research_contribution_files_reference($file_id)
    {
        $data  = "*";
        $table = "research_contribution_files_references";
        $where=" where file_id='$file_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function get_all_research_contribution_text_reference($text_id)
    {
        $data  = "*";
        $table = "research_contribution_text_references";
        $where=" where text_id='$text_id'";
        $res = $this->getAllData($data,$table,$where);
        return $res;
    }

    public function report($research_id,$report,$reporter_id)
    {
        $report_id=$this->generate_research_report_id();
        $data  = ["report_id"=>$report_id,"report"=>$report,"reporter_id"=>$reporter_id,"research_id"=>$research_id,"date_reported"=>date("Y-m-d H:i:s")];
        $table = "research_report";
        $res = $this->insertData($data,$table);
        if($res){
            $message=json_encode(["status"=>1,"message"=>"Report successfully sent"]);
        }else{
            $message=json_encode(["status"=>0]);
        }
        return $message;
    }
    public function generate_research_report_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_research_report_id();
        return intval($id) + 1;
    }
    public function get_last_research_report_id(){
        $data = array("report_id");
        $table = "research_report";
        $where = " ORDER BY id DESC";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['report_id'];
        } 
    }

    public function fetch_all_reports(){
        $data = "*";
        $table = "research_report";
        $where = " ORDER BY id DESC";

        $result = $this->getAllData($data, $table, $where);
        return $result;
    }

    

    


}