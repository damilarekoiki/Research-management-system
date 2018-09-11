<?php
    include ("app/init.php");

    if(isset($_GET['logout'])){
        $master->logout();
        $master->redirect("index.php");
    }
?>