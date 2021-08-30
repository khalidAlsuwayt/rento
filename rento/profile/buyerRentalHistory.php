<?php 

$title = 'Buyer Rental History';

require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../template/header.php';


if(!isset($_SESSION['logged'])){ 
    header('location: '.$config['app_url'].'template/notUser.php');
    die();
}



$search ='';
    $filter = '';
    $errors =[];
    $query=[];
    $userId = $_SESSION['id'];
    $query = $mysqli->query("select * from itemList tl join item i on tl.itemId = i.id  
    where tl.buyerId = '$userId' order by tl.created_at ");


    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        print_r($_POST);

        $itemId = $_POST['itemId'];
        if ($_POST['start']) {
            $mysqli->query("update itemList set state = 'inRent' where itemId = '$itemId' ");
            header("location: buyerRentalHistory.php");
        } 
        else if ($_POST['end']) {
            $mysqli->query("update itemList set state = 'finish' where itemId = '$itemId' ");
            $sellerId = $_POST['sellerId'];
            $_SESSION['rate'] = 1;
            header("location: rating.php?id=$itemId&userId=$userId&sellerId=$sellerId");
        }
    }
    $_POST = array();
?>
<br><br>
<table>
  <tr>
    <th>Name of The item</th>
    <th>State</th>
    <th>Seller</th>
    <th>Buyer</th>
    <th>Period</th>
    <th>Action</th>
  </tr>
  <?php foreach($query as $q){?>
    <tr>
    <td><?php echo $q['name'] ?></td>
    <td><?php echo $q['state'] ?></td>
    
    <td><a href="<?php echo $config['app_url'].'profile/index.php?id='.$q['sellerId'] ?>"><?php echo $q['sellerName'] ?></a></td>
    <td><a href="<?php echo $config['app_url'].'profile?id='.$q['buyerId'] ?>"><?php echo $q['buyerName'] ?></a></td>
    <td>
    From :   <?php echo $q['fromDate'] ?><br>
    To : <?php echo $q['toDate'] ?>
    </td>
    <td>
    <form action="" method="post">
    <input type="hidden" name="sellerId" value="<?php echo $q['sellerId'] ?>">
        <input type="hidden" name="itemId" value="<?php echo $q['itemId'] ?>">
    <?php
    if($q['state'] == 'rentRequest'){
        echo 'Waiting For Response';
    }
    else if ( ($q['state'] == 'accept') && (date('Y-m-d') >= $q['fromDate']) ){
        echo '<input type="submit" name="start" value="Start Renting" class="btn btn-primary">';
    }
    elseif ( ($q['state'] == 'accept') ){
        echo 'Waiting For Renting Date';
    }
    else if ($q['state'] == 'reject'){
        echo 'Rejected';
    }
    else if ($q['state'] == 'inRent'){
        echo '<input type="submit" name="end" value="End Renting" class="btn btn-primary">';
    }
    else if ($q['state'] == 'finish'){
        echo 'Rent Finished';
    }

    ?>
    </form>

    </td>
    </tr>
  <?php } ?>
</table>




<?php require_once __DIR__.'/../template/footer.php'?>