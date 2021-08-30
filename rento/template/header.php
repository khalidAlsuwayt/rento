<?php require_once __DIR__.'/../config/app.php';
echo __DIR__.'/../config/app.php'; 
//session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['app_name']. ' | '.$title ?></title>
        <meta charset="UTF-8">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <style>

#blah
{
 max-width:300px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
#map {
        position: relative;
        top: 0px;
        bottom: 100px;
        height: 550px;
        width: 660px;
    }

    .geocoder {
        position: static;
    }
      </style>
    </head>
    <body class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo $config['app_url'].'index.php'?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $config['app_url'].'item/itemList.php'?>">Item List</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="<?php echo $config['app_url'].'item/offer.php'?>" >Offer Item</a>
        </li>
      </ul>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php
        if(isset($_SESSION['logged'])){?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo $_SESSION['name'];?></a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="<?php echo $config['app_url'].'profile/sellerRentalHistory.php'?>">Seller Rental History</a></li>
            <li><a class="dropdown-item" href="<?php echo $config['app_url'].'profile/buyerRentalHistory.php'?>">Buyer Rental History</a></li>
            <li><a class="dropdown-item" href="<?php echo $config['app_url'].'item/draft.php'?>">Draft</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>  
          </li>

          <li>
            <a class="nav-link" href="<?php echo $config['app_url'].'logout.php'?>">Log Out</a>
          </li>
          <?php }else{       
      ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $config['app_url'].'login.php'?>">Login</a>  
        </li>
        <li>
          <a class="nav-link" href="<?php echo $config['app_url'].'register.php'?>">Register</a>
        </li>
        <?php }?>
      </ul>
      
    </div>
  </div>
</nav>
<?php 
/*
<form action="" method="post">
        <input class="form-control me-2" type="text" name="hsearch" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
if($_SERVER['REQUEST_METHOD'] == 'POST'){

  $search = mysqli_real_escape_string($mysqli, $_POST['search']);
  header("location: ".$config['app_url'].'item/itemList.php'.'?search='.$search);

}*/