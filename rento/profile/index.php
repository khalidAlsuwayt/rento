<?php
$title = 'Seller Rental History';

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../template/header.php';


?>

<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = $_SESSION['id'];
}

$query = $mysqli->query("select * from rating where sellerId = '$id' ")->fetch_all();
$user = $mysqli->query("select * from users where id = '$id' ")->fetch_assoc();
//echo '<pre>';print_r($query);echo '<pre>';
echo '<hr>';
$rate = $mysqli->query("SELECT (SUM(score) / COUNT(score) ) rate FROM `rating` WHERE sellerId = '$id'")->fetch_assoc();

?>

<h1><?php echo $user["name"] ?></h1>
<br><br>

<h3>Rating : <?php echo round($rate['rate'], 2).'/5' ;?></h3>
<br>
<h3>Comments</h3><hr>

<?php foreach( $query as $q){?>
    <div class="mb-3">
        <a style="text-decoration: none;" href="<?php echo 'index.php?id='.$q['3'] ?>"> <?php echo $q['4']; ?> </a>
        <textarea readonly name="comment" id="comment" class="form-control" ><?php echo $q['6'] ?></textarea>
    </div>


    <?php 
//echo '<pre>';print_r($q);echo '<pre>';
}?>
<?php 

require_once __DIR__ . "/../template/footer.php" ?>