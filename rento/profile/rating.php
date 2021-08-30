<?php 

$title = 'Rating';

require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../template/header.php';


if($_SESSION['rate'] != 1){ 
    header('location: '.$config['app_url'].'template/notUser.php');
    die();
}

$id = $_GET['id'];
$userId = $_GET['userId'];
$sellerId = $_GET['sellerId'];

$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $rate = mysqli_real_escape_string($mysqli, $_POST['rate']);
    $comment = mysqli_real_escape_string($mysqli, $_POST['comment']);

    if(empty($rate)){array_push($errors,'Rate is Required');}

    if(!count($errors)){
        $name = $_SESSION['name'];
        $mysqli->query("insert into rating (sellerId, buyerId, buyerName, score, comment) 
        values('$sellerId', '$userId', '$name', '$rate', '$comment')");
        $_SESSION['rate'] = 0;
        $_SESSION['msg'] = "Thank You For Your Rating";
        header("location: ".$config['app_url']);
    }
}
?>

<div style="text-align: center;">
<h1>Please Rate The Seller and Write a Comment </h1>

<?php require __DIR__.'/../template/errors.php'?>

<form action="" method="post">
<input type="number" min=1 max=5 name="rate"> / 5 <br>
<label for="comment">Comment</label>
<input type="text" name="comment">
<button type="submit" >Rate</button>
</form>
</div>

<?php require_once __DIR__.'/../template/footer.php'?>