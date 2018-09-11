<?php
class Coordinator extends Master
{
    // protected $user_role;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////setters///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   public  function __construct($db_conn,$lang)
    {       Master::__construct($db_conn,$lang);
        // $this->user_role = $user_role;
          
    }

    public function remove_research($research_id,$admin_id)
    {
        if($this->is_admin($admin_id)){
            $research = new Research($this->db,$this->lang);
            $research->remove($research_id);
        }

    
        

    }
    public function remove_user($user_id,$admin_id)
    {
        if($this->is_admin($admin_id)){
            $data  = array("user_id"=>$user_id);

            $stmt = $this->db->prepare("DELETE FROM user WHERE user_id= :user_id");
            if($stmt->execute($data)){
                $message = json_encode(array('status' => 1,"message"=>"User successfully removed"));
            
            }else{
                $message = json_encode(array('status' => 0, "message"=>"User could not be deleted"));
            }
            return $message;
        }
    }

    public function remove_admin($admin_id_2,$admin_id)
    {
        if($this->is_admin($admin_id)){
            $data  = array("admin_id_2"=>$admin_id_2);

            $stmt = $this->db->prepare("DELETE FROM admin WHERE admin_id= :admin_id_2");
            if($stmt->execute($data)){
                $message = json_encode(array('status' => 1,"message"=>"Admin successfully removed"));
            
            }else{
                $message = json_encode(array('status' => 0, "message"=>"Admin could not be removed"));
            }
            return $message;
        }

    }

    public function add_admin($surname,$other_names,$email,$password,$admin_id)
    {
        $admin_id_2=$this->generate_admin_id();
        if($this->is_admin($admin_id)){
            $data  = array("admin_id"=>$admin_id_2,"surname"=>$surname,"other_names"=>$other_names,"email"=>$email,"password"=>$password,"profile_pix"=>"","date_registered"=>date("Y-m-d H:i:s"));

            $table = "admin";
            $res = $this->insertData($data,$table);
            if($res){
                $message = json_encode(array('status' => 1,"message"=>"Admin added successfully"));
            
            }else{
                $message = json_encode(array('status' => 0, "message"=>"There was a problem add admin"));
            }
            return $message;
        }

    }

    public function add_user($surname,$other_names,$email,$password,$user_role,$admin_id)
    {
        
        if($this->is_admin($admin_id)){
            $this->register_user($surname,$other_names,$email,$password,$user_role);
        }

    }

    public function approve_as_coordinator($user_id,$admin_id)
    {
        if($this->is_admin($admin_id)){
            $data  = array("approved_as_coordinator"=>1);

            $table = "user";
            $where=" where user_id='$user_id'";
            $res = $this->updateData($data,$table,$where);
            if($res){
                $message = json_encode(array('status' => 1,"message"=>"User successfully approved as coordinator"));
            
            }else{
                $message = json_encode(array('status' => 0, "message"=>"Could not approve user as coordinator"));
            }
            return $message;
        }
    }

    public function decline_as_coordinator($coordinator_id,$admin_id)
    {
        if($this->is_admin($admin_id)){
            $data  = array("approved_as_coordinator"=>0);

            $table = "user";
            $where=" where user_id='$user_id'";
            $res = $this->updateData($data,$table,$where);
            if($res){
                $message = json_encode(array('status' => 1,"message"=>"User successfully approved as coordinator"));
            
            }else{
                $message = json_encode(array('status' => 0, "message"=>"Could not approve user as coordinator"));
            }
            return $message;
        }
    }

    public function is_admin($admin_id)
    {
        $data = "*";
        $table = "admin";
        $where = " where admin_id='$admin_id'";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return false;
        }else{
            return true;
        } 
    }

    public function login($email,$password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM admin WHERE email=:email AND password = :password LIMIT 1");
            $stmt->execute(array(':email' => $email, ':password' => $password));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {

                $_SESSION['email'] = $userRow['email'];
                $_SESSION['admin_id'] = $userRow['admin_id'];
                $_SESSION['surname'] =  $userRow['surname'];
                $_SESSION['other_names'] =  $userRow['other_names'];
                exit(json_encode(["status"=>1,"message"=>"Login successful","url"=>"index.php"]));

            } else {
                exit(json_encode(["status"=>0,"message"=>$password]));
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}