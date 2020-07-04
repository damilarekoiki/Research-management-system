<?php
include ("../app/init.php");

if(isset($_POST['register'])){
    $surname=$_POST['surname'];
    $other_names=$_POST['other_names'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    

    if(!empty($surname)&&!empty($other_names)&&!empty($email)&&!empty($password)){
        if(isset($_POST['user_role'])){
            $user_role=$_POST['user_role'];
            if($user_role==0 or $user_role==1){
                $result=$master->register_user($surname,$other_names,$email,$password,$user_role);
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
        $result=$master->user_login($email, $password);
        exit($result);
    }else{
        exit(json_encode(["status"=>0,"message"=>"Please fill all fields"]));
    }
}

if(isset($_POST['fetch_users_to_add_follow'])){
    $all_users=$master->fetch_all_users();
    $research_id=$_POST['research_id'];

    foreach ($all_users as $user) {
        $fetched_user_id=$user['user_id'];
        $name=$user['surname']." ". $user['other_names'];
        $profile_pix=$user['profile_pix'];

        $share_msg="";
        $share_disable="";
        if($research->is_shared_to_this_other_researcher($research_id,$fetched_user_id)){
            $share_msg="Shared";
            $share_disable="disabled";
        }else{
            $share_msg="Share";
        }

        echo "<div class='row' style='margin-top:10px'> 
                <div class='col-md-9'><img src='../$profile_pix' class='img img-responsive' height='18px'> $name</div>
                <div class='col-md-3'><button class='btn btn-primary share-to-researcher' style='text-transform:none;' $share_disable research-id='$research_id' researcher_id='$user_id' shared-to='$fetched_user_id'>$share_msg</button></div>
            </div>";
    }

    echo "<script>
        $('.share-to-researcher').click(function(){
            btn=$(this);
            researchId=$(this).attr('research-id');
            researcherId=$(this).attr('researcher-id');
            sharedTo=$(this).attr('shared-to');

            formData=new FormData();
            formData.append('share_research',1);
            formData.append('share_to_few',1);
            formData.append('research_id',researchId);
            formData.append('researcher_id',researcherId);
            formData.append('shared_to',sharedTo);


            $.ajax({
                url:'../parser/research_parser.php',
                data:formData,
                type:'post',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    data=JSON.parse(data);
                    if(data.status==1){
                        btn.text('Shared');
                        btn.attr('disabled',true)
                    }
                    // $('#modalBody-users').html(data)
                    // data=JSON.parse(data);
                    // $('#loginBtn').val('Login')
                    // if(data.status==1){
                    //     $('#errorMsg').html('<div class='alert alert-success'>'+data.message+'</div>');
                    //     setTimeout(() => {
                    //         window.location=data.url;                        
                    //     }, 3000);
                    // }else{
                    //     $('#errorMsg').html('<div class='alert alert-danger'>'+data.message+'</div>');
                    // }
                }
            })

        })
    </script>";
}


?>