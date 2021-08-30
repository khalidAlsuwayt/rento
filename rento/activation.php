<?php 
$title = 'Avtivation';

require_once 'config/app.php';
require_once 'config/database.php';
require_once 'template/header.php';
//session_start();
if(!isset($_SESSION['logged'])){
    header("location: ".$config['app_url']."template/notUser.php");
}

if(!isset($_GET['token'])){
    header("location: ".$config['app_url']);
}

$token = $_GET['token'];
$id = $_SESSION['id'];
$query = $mysqli->query("select token from users where id = '$id'")->fetch_assoc();

if($token == $query['token']){
    $mysqli->query("update users set isActive = 1 where id = '$id'");
    $_SESSION['msg'] = "Your Account has been Activated";
    header("location: index.php");
}else{
    echo 'Wrong URL';
    header("location: index.php");
}