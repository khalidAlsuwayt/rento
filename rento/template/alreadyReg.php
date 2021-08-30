<?php
require_once __DIR__.'/../config/app.php';
require_once 'header.php';

echo '<h1>You are already Registered</h1>';
header("refresh:3;url =  ".$config['app_url']);
