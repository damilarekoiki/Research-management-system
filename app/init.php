<?php
error_reporting(E_ALL);
ini_set('display_errors',"on");
ob_start();
session_start();

include ('config/config.php');
include ('config/db_config.php');

global $db_conn;
if(isset($_GET['lang'])){
    $lang = htmlspecialchars($_GET['lang']);
    include "config/".$lang.".php";
    $_SESSION['lang'] = $lang;
}else{
    if(isset($_SESSION['lang'])){
        $lang = htmlspecialchars($_SESSION['lang']);
        include "config/".$lang.".php";
    }else{
        include "config/en.php";

    }

}
$lang = $lang_array;
global $lang;
include "classes/Master.php";
include "classes/Research.php";
include "classes/Researcher.php";
include "classes/Coordinator.php";
include "classes/Admin.php";

// include "classes/On_Air_Media.php";
// include "classes/Display.php";
// include "classes/User.php";


$master = new Master($db_conn,$lang);
$research = new Research($db_conn,$lang);
$researcher = new Researcher($db_conn,$lang);
$coordinator = new Coordinator($db_conn,$lang);
$admin = new Admin($db_conn,$lang);


    $home="#";
    if(isset($_SESSION['email'])){
        if(isset($_SESSION['role'])){
            if($_SESSION['role']==0 || $_SESSION['role']==1){
                $home="user/index.php";
            }else{
                $home="admin/index.php";
            }
        }
        
    }

    if(isset($_SESSION['user_id'])){
        $user_id=$_SESSION['user_id'];
        $user_data=$master->get_user_data($user_id);

        $user_img=$user_data['profile_pix'];
        $user_surname=$user_data['surname'];
    }

    if(isset($_SESSION['admin_id'])){
        $user_id=$_SESSION['admin_id'];
        $user_data=$admin->get_admin_data($user_id);
        $user_img=$user_data['profile_pix'];
        $user_surname=$user_data['surname'];
    }

    

    // echo "<script>alert('".SITE_PATH."')</script>";
        

// $user = new User($db_conn,$lang_array);

?>