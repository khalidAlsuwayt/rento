<?php

$title = 'Seller Rental History';

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../template/header.php';

if (!isset($_SESSION['logged'])) {
  header('location: ' . $config['app_url'] . 'template/notUser.php');
  die();
}

$search = '';
$filter = '';
$errors = [];
$query = [];
$userId = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  print_r($_POST);
  $itemId = $_POST['itemId'];
  if ($_POST['accept']) {
    $mysqli->query("update itemList set state = 'accept' where itemId = '$itemId' ");
    $mysqli->query("update item set isAvailable = '0' where id = '$itemId' ");
  } 
  else if ($_POST['reject']) {
    $mysqli->query("update itemList set state = 'reject' where itemId = '$itemId' ");
  } 
  else if ($_POST['withdraw']) {
    $mysqli->query("update itemList set state = 'draft' where itemId = '$itemId' ");
    $mysqli->query("update item set isAvailable = '0' where id = '$itemId' ");
    header("location: sellerRentalHistory.php");
  } 
  else if ($_POST['edit']) {
    header("location: " . $config['app_url'] . "item/editItem.php?id=" . $itemId);
  }

}
$query = $mysqli->query("select * from itemList tl join item i on tl.itemId = i.id  
    where tl.sellerId = '$userId' order by tl.created_at ");

print_r($query->fetch_assoc());
//$itemId = $query->fetch_assoc()['itemId'];
//print_r($_SESSION);
$_POST = array();
echo $_SERVER['REQUEST_METHOD'];
echo '<hr>';


echo 3;
?>
<table>
  <tr>
    <th>Name of The item</th>
    <th>State</th>
    <th>Seller</th>
    <th>Buyer</th>
    <th>Period</th>
    <th>Action</th>
  </tr>
  <?php foreach ($query as $q) { ?>
    <tr>
      <td><?php echo $q['name'] ?></td>
      <td><?php echo $q['state'] ?></td>
      <td><a href="<?php echo $config['app_url'] . 'profile/index.php?id=' . $q['sellerId'] ?>"><?php echo $q['sellerName'] ?></a></td>
      <td><a href="<?php echo $config['app_url'] . 'profile?id=' . $q['buyerId'] ?>"><?php echo $q['buyerName'] ?></a></td>
      <td>
        From : <?php echo $q['fromDate'] ?><br>
        To : <?php echo $q['toDate'] ?>
      </td>
      <td>
        <form action="" method="post">
          <input type="hidden" name="itemId" value="<?php echo $q['itemId'] ?>">
          <?php

          if ($q['state'] == 'offer') {
            echo '<input type="submit" name="withdraw" value="Withdraw" class="btn btn-primary">';
          } 
          elseif ($q['state'] == 'draft') {
            echo '<input type="submit" name="edit" value="Edit" class="btn btn-primary">';
          } 
          elseif ($q['state'] == 'rentRequest') {
            echo '<input type="submit" name="accept" value="Accept" class="btn btn-success">';
            echo '<input type="submit" name="reject" value="Reject" class="btn btn-danger">';
          } 
          elseif (($q['state'] == 'accept')) {
            echo 'Waiting For Renting Date';
          } 
          else if ($q['state'] == 'reject') {
            echo 'Rejected';
          } 
          else if ($q['state'] == 'inRent') {
            echo 'In Rent';
          } 
          else if ($q['state'] == 'finish') {
            echo 'Rent Finished';
          }

          ?>
        </form>

      </td>
    </tr>
  <?php } ?>
</table>


<?php



require_once __DIR__ . '/../template/footer.php' ?>