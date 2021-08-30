<?php
require_once __DIR__.'/../config/app.php';
require_once 'header.php';

echo '<h1>You Need To Be Logged In To Access This Page</h1>';
header("refresh:5;url =  ".$config['app_url']);
