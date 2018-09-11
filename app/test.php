<?php
ob_start();
include "init.php";
$myvar = ob_get_clean();
var_dump($myvar);




?>