<?php 
require_once 'config/app.php';
require_once 'config/database.php';
require_once 'template/header.php';

session_unset();
setcookie('log','',time() - 1);
echo '<h1>You are Logged Out</h1>';
header("refresh:1;url =  ".$config['app_url']);
