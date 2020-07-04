<?php
error_reporting(E_ALL);
ini_set('display_errors',"on");
class Master
{
    protected $db;
    protected $lang;


    
        function __construct($db_conn,$lang)
        {
            $this->db = $db_conn;
            $this->lang = $lang;
        }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////ACTIONS//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function user_exist($email)
    {
        $check=$this->getData("*","user"," where email='$email'");
        if(count($check)>0){
            return true;
        }else{
            return false;
        }
    }
    
        public function register_user($surname,$other_names,$email,$password,$user_role,$is_approved_as_coordinator=2)
        {
            $check_user_exist=$this->user_exist($email);        
            if($check_user_exist==false){
                $user_id = $this->generate_user_id();
                $activation_code = $this->generate_user_activation();
                $date_reg = strtotime("now");
                $activation_code_validity = strtotime("+1 day");

                

                //make the directory
                // $create_pix_directory = mkdir("../user/assets/profile_pix");
                // $create_user_directory = mkdir("../user/assets/profile_pix/$user_id");
                
                $path = "app/assets/avatar.png";

                // if($create_user_directory){

                //     //check if pix is uploaded
                //     if(isset($_FILES['profile_pix'])){
                //         // return "jdkdid";
                //         if(is_uploaded_file($_FILES['profile_pix']['tmp_name'])){
                //             $file_temp = $_FILES['profile_pix']['tmp_name'];
                //             $file_name = $_FILES['profile_pix']['name'];
                //             $ext =  pathinfo($file_name,PATHINFO_EXTENSION);
                //             if($ext == "jpg" || $ext == "png" || $ext == "PNG" ){

                //                 $file_name =  "".$file_name;

                //                 $result =  move_uploaded_file($file_temp,$file_name);
                //                 if($result){
                //                     $path =  $file_name;

                //                 }else{
                //                     $path = "app/assets/avatar.png";
                //                 }

                //             }else{
                //                 //use avatar as logo
                //                 $path = "app/assets/avatar.png";
                //             }
                //         }else{
                //             //use avatar as logo
                //             $path = "app/assets/avatar.png";
                //         }
                    
                //     }
                // }

                $sql = "INSERT INTO user SET
                        user_id = :user_id,
                        surname = :surname,
                        other_names = :other_names,
                        user_role = :user_role,
                        email = :email,
                        password = :password,
                        activation_code = :activation_code,
                        account_status = :account_status,
                        date_registered = :date_registered,
                        activation_code_validity = :activation_code_validity,
                        profile_pix = :profile_pix,
                        is_approved_as_coordinator= :is_approved_as_coordinator
                        ";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(array('user_id'=>$user_id,'surname'=>$surname,'other_names'=>$other_names,'user_role'=>$user_role,'email'=>$email,'password'=>$password,'activation_code'=>$activation_code,'account_status'=>1,'date_registered'=>date("Y-m-d H:i:s"),'activation_code_validity'=>$activation_code_validity,'profile_pix'=>$path,"is_approved_as_coordinator"=>$is_approved_as_coordinator));
                $message = json_encode(array('status'=>1,"message"=>"Registration successful, redirecting to login","url"=>"login.php"));
                return $message;

            }else{
                $message = json_encode(array('status'=>0,"message"=>"User already exists"));
                return $message;
            }

        }
    // generate company id from previously generated id
    public function generate_user_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_user_id();
        return intval($id) + 1;
    }
    public function get_last_user_id(){
        $data = array("user_id");
        $table = "user";
        $where = " ORDER BY id DESC";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['user_id'];
        } 
    }

    public function generate_admin_id(){
        /*this method generate id for new media outfit*/
        $id = $this->get_last_admin_id();
        return intval($id) + 1;
    }
    public function get_last_admin_id(){
        $data = array("admin_id");
        $table = "admin";
        $where = " ORDER BY id DESC";

        $check = $this->getData($data, $table, $where);
        if(empty($check)){
            return 0;
        }else{
            return $check['admin_id'];
        } 
    }

    //generating user activation code
    public function generate_user_activation(){
        /*this method generate id for new media outfit*/
        $activation_code = uniqid(rand());
        return $activation_code;
    }

    public function fetch_all_users()
    {
        $data = "*";
        $table = "user";

        $data = $this->getAllData($data, $table);
        return $data;
    }


    // public function construct_new($array1,$array2){
    //     $new_array = array();
    //     for($i= 0;$i<count($array1);++$i){
    //         if(isset($array1[$i]) && isset($array2[$i])) {
    //             $new_array[str_replace(' ','_',$array1[$i])] =  $array2[$i];
    //         }

    //     }
    //     return $new_array;
    // }

    // public function activate_media_outfit($contact_person_email,$activation_code){
    //     $exp_date = $this->get_media_outfit_expiry($contact_person_email);
    //     $now = strtotime('now');
    //     $exp_date = strtotime($exp_date);
    //     if( $now > $exp_date ){
    //         $message = json_encode(array('status'=>0,"message"=>$this->lang['activation_exp_date']));
    //         //remove user from database
    //         return $message;
    //     }
    //     $media_outfit_activation_code = $this->get_media_outfit_activation($contact_person_email);
    //     if($media_outfit_activation_code == $activation_code){
    //         $this->update_media_outfit_activation_code($contact_person_email);
    //         $this->set_user_progress($contact_person_email,"peak_period","ads_type",$this->get_media_outfit_id($this->logged_in_user()));
    //         $message = json_encode(array('status'=>1,"message"=>$this->lang['account_activation']));
    //         return $message;
            
    //     }else{
    //         $message = json_encode(array('status'=>0,"message"=>$this->lang['account_activation_failure']));
    //         return $message;
    //     }

    // }

    // public function update_media_outfit_activation_code($contact_person_email){
    //     $new_activation = $this->generate_user_activation();
    //     $data = array("activate_account_code"=>$new_activation,"account_status"=>1);
    //     $table = "company_users";
    //     $where = "WHERE contact_person_email = '$contact_person_email'";

    //     $check = $this->updateData($data, $table, $where);
    // }

    public function user_login($email, $password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user WHERE email=:email AND password = :password  AND account_status = 1 LIMIT 1");
            $stmt->execute(array(':email' => $email, ':password' => $password));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {

                $_SESSION['email'] = $userRow['email'];
                $_SESSION['user_id'] = $userRow['user_id'];
                $_SESSION['surname'] =  $userRow['surname'];
                $_SESSION['other_names'] =  $userRow['other_names'];
                $_SESSION['role'] =  $userRow['user_role'];
                return (json_encode(["status"=>1,"message"=>"Login successful","url"=>"index.php"]));

            } else {
                return (json_encode(["status"=>0,"message"=>"Invalid username or password"]));
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function get_user_data($user_id)
    {
        $data=$this->getData("*","user"," where user_id='$user_id'");
        return $data;
    }

    // public function send_mail($to,$subject,$content){
    //     $body = '<p>
    //                 Hi,
    //                 <br>'.$content.'
    //                 <br>
    //                 Thanks
                    
    //                 </p>';
    //    $headers = "MIME-Version: 1.0" . "\r\n";
    //     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // // More headers
    //   $headers .='From:<noreply@mediapay.com.ng>' . "\r\n";

    //   $res = mail($to,$subject,$body,$headers);
    //     return $res;
    // }

    public function make_date($day="today"){
        if($day == "today"){
            return date();
        
        }
    }



        ///////////////////////////////////////Database Interface//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // public function load_language($lang_file){
        //     $langs = file($lang_file);
        
        //     foreach($langs as $lang ){

        //     }

        // }


    public function getData($data, $table, $where = '')
    {
        try {
            if ($data != '*') {
                $selections = implode(', ', $data);
            } else {
                $selections = '*';
            }

            $stmt = $this->db->prepare("SELECT {$selections} FROM `$table` " . $where . " LIMIT 1");
            $stmt->execute();
            $settings_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                return $settings_data;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

        


    public function updateData($array, $table, $where = '')
    {

        try {

            $fields = array_keys($array);
            $values = array_values($array);
            $fieldlist = implode(',', $fields);
            $qs = str_repeat("?, ", count($fields) - 1);
            $firstfield = true;

            $sql = "UPDATE `$table` SET";

            for ($i = 0; $i < count($fields); $i++) {
                if (!$firstfield) {
                    $sql .= ", ";
                }
                $sql .= " " . $fields[$i] . "= ? ";
                $firstfield = false;
            }
            if (!empty($where)) {
                $sql .= $where;
            }
            $sth = $this->db->prepare($sql);
            return $sth->execute($values);

            return $sth;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertData($array, $table)
    {

        try {

            $fields = array_keys($array);
            $values = array_values($array);
            $fieldlist = implode(',', $fields);
            $qs = str_repeat("?,", count($fields) - 1);
            $firstfield = true;

            $sql = "INSERT INTO `$table` SET";

            for ($i = 0; $i < count($fields); $i++) {
                if (!$firstfield) {
                    $sql .= ", ";
                }
                $sql .= " " . $fields[$i] . "=?";
                $firstfield = false;
            }

            $sth = $this->db->prepare($sql);
            return $sth->execute($values);

            return $sth;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAllData($data, $table, $where = '')
    {
        try {
            if ($data != '*') {
                $selections = implode(', ', $data);
            } else {
                $selections = '*';
            }


            $stmt = $this->db->prepare("SELECT {$selections} FROM `$table` " . $where . "");
            $stmt->execute();
            $settings_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                return $settings_data;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function resolve_to_time($date){
        $date_array = getdate($date);
        $month = $date_array['month'];
        $day =  $date_array['mday'];
        $year=  $date_array['year'];
        $date_string = $day."-".$month."-".$year;
        return $date_string;
    }

    // public function forgot_password($user){
    //     $user_exist = $this->user_exist($user);
    //     if(!$user_exist){
    //             //

            
    //               $message = json_encode(array('status' => 0, "message" =>$this->lang['email_exist_error']));
    //     }else{
    //         $to = $user;
    //         $subject = "Password Reset";
    //         $activation = $this->get_media_outfit_activation($user); 
    //          $content =  ' <br>
    //                 Follow the below link to reset your Mediapay account password
    //               <br>
    //               <a href="shall be set later.atlas.com/password_reset.php?activate="'. $activation.'&email='.$email.' >Password reset</a>

                
    //          <br>
    //          <br>';
    //     //   $email_status = $this->send_mail($to,$subject,$content);  
    //       if($email_status){
            
        
    //            $message = json_encode(array('status' => 1, "message"=>$this->lang['email_sent_successfully']));
    //       }else{
    //            $message = json_encode(array('status' => 0, "message"=>$this->lang['email_sent_error']));
    //       }
    //     }
    //         return $message;
    // }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Getters/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                                                    

    public function redirect($url)
    {
        header("Location: " . $url . "");
    }

    public function logout()
    {
        if (isset($_SESSION['email'])) {
            unset($_SESSION['email']);
            session_destroy();

        }
        
        return true;
    }


    

    public function delete_file($file_path)
    {
        # code...
    }

    public function upload_file($file,$file_path)
    {
        # code...
    }



}








?>