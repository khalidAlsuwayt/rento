<?php
$title = 'Home Page';

require_once 'config/app.php';
require_once 'config/database.php';
require_once 'template/header.php';
//session_start();


?>
<br>
<h1 style="text-align: center;">Welcome to Rento</h1>
<br>
<hr>


<?php

//echo 'PHP version: ' . phpversion();

if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
    echo '<div class="alert alert-success" role="alert"> ';
    echo $_SESSION['msg'];
    echo '</div>';
    $_SESSION['msg'] = '';
}
//print_r($_SESSION);
function getItems($category)
{
    $mysqli = new mysqli("localhost", "root", "", "rento");

    if ($mysqli->connect_error) {
        die("Error" . $mysqli->connect_error);
    } else {
        //echo "Connected to mysql\r\n";
        //echo "<br>";
    }
    if (empty($category)) {
        $query = $mysqli->query("select * from item where isAvailable = 1 order by created_at desc limit 4");
    } else {
        $query = $mysqli->query("select * from item where UPPER(`category`) like UPPER('$category') and isAvailable = 1 order by created_at desc limit 4");
    }

?>
    <div class="row">
        <?php
        foreach ($query as $q) { ?>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo 'images/' . $q['picture'] ?>" class="card-img-top" alt="...">
                    <hr>
                    <div class="card-body">
                        <a class="btn btn-secondary btn-lg" href="item/itemPage.php?id=<?php echo $q['id'] ?>"><?php echo $q['name'] ?></a>
                        <p class="card-text"><?php echo "Price: " . $q['price'] . ' SAR/Day'  ?></p>
                    </div>
                </div>
            </div>
        <?php
        }

        ?>
    </div>
<?php
}



?>
<br>
<h1>Latest Items</h1>
<a href="item/itemList.php">View All</a>
<hr>
<?php getItems(''); ?>
<br>
<br>
<h1>Sport</h1>
<a href="item/itemList.php?category=sport">View All</a>
<hr>
<?php getItems('sport'); ?>
<br>
<br>






<?php require_once 'template/footer.php'

/*

<div class="row">
    <?php
    foreach ($query as $q) { ?>
        <div class="col-md-4">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <img src="<?php echo 'images/' . $q['picture'] ?>" class="img-thumbnail" alt="...">
                    <a class="btn btn-secondary btn-lg" href="coursePage.php?id=<?php echo $q['id'] ?>"><?php echo $q['name'] ?></a>
                    <p class="card-text"><?php echo $q['description'] ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><?php echo "Category: " . $q['category'] ?></li>
                    <li class="list-group-item"><?php echo "Price: " . $q['price'] . ' SAR' ?></li>
                </ul>

            </div>
        </div>
    <?php
    }

    ?>
</div>
*/
?>