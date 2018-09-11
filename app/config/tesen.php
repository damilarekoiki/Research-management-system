<?php

	include_once '../../app/init.php';

    $user=new User($db_conn,$lang);
    $data = $user->enduser_check_day_ads_dont_fit('2017-10-11','2017-10-30',3,3);
    $data1 = $user->enduser_book_for_ad('2017-10-11','2017-10-30',3,3);
    echo $data1;
    var_dump($data);
    echo "<br>";
    echo date('2017-10-11');


?>