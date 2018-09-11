<?php

$config['SITE_URL'] = 'research_management';

$config['SITE_ASSETS'] = $config['SITE_URL'].'assets/';
$config['SITE_PATH'] = $_SERVER['DOCUMENT_ROOT'].'/research_management/';



//DATABASE CONFIGURATION
$config['DB_HOST'] = 'localhost';
$config['DB_USER'] = 'root';
$config['DB_PASS'] = '';
$config['DB_NAME'] = 'research_management';


//EMAIL CONFIGURATION



foreach ($config as $key => $value) {
	define($key, $value);
}  