<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$mysqli = new mysqli("localhost", "root", "", "rento");
session_start();


if ($mysqli->connect_error) {
	die("Error" . $mysqli->connect_error);
} else {
	echo "Connected to mysql\r\n";
	echo "<br>";
}/*
if (isset($_COOKIE['log'])) {
	$idd = $_COOKIE['log'];
	$checkUser = $mysqli->query("select id,name,email from users where id = '$idd'")->fetch_assoc();
	$_SESSION['logged'] = true;
	$_SESSION['id'] = $checkUser['id'];
	$_SESSION['email'] = $checkUser['email'];
	$_SESSION['name'] = $checkUser['name'];
	$_SESSION['msg'] = "Welcome Back " . $checkUser['name'];
}*/
