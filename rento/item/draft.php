<?php

$title = 'Draft';

require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../template/header.php';

if(!isset($_SESSION['logged'])){ 
    header('location: '.$config['app_url'].'template/notUser.php');
    die();
}
?>
<br>
<h1 style="text-align: center;">All Drafted Items</h1>
<br>
<hr>
    
        
    <?php
    $search ='';
    $filter = '';
    $errors =[];
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        $_SESSION['msg'] = '';
    }
    //print_r($_SESSION);
    $userId = $_SESSION['id'];
    $query = $mysqli->query("select * from itemList tl join item i on tl.itemId = i.id where tl.state = 'draft' 
    and i.userId='$userId' order by i.created_at desc");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        print_r($_POST);

        $search = mysqli_real_escape_string($mysqli, $_POST['search']);
        $filter = mysqli_real_escape_string($mysqli, $_POST['filter']);
    
        if(empty($search)){array_push($errors,'Name is Required');}    
    
        if(!count($errors)){
            if($filter == 'all'){
                $query = $mysqli->query("select * from item where UPPER(name) like UPPER('%$search%') 
                OR UPPER(description) like UPPER('%$search%') order by created_at desc");
            }else{
                $query = $mysqli->query("select * from item where UPPER(name) like UPPER('%$search%') 
                OR UPPER(description) like UPPER('%$search%') and category = '$filter' order by created_at desc");    
            }
            
        }
    }
    
    
    function fun(){
        echo 'FUN';
    }

    ?>

<h1>Search</h1>

<?php //require 'template/errors.php'?>

<form action="" method="post" >

    <div class="mb-3">
        <input type="text" name="search" id="search" class="form-control" placeholder="Enter Search Word" value="<?php echo $search ?>">
    </div>
    <input type="radio" name="filter" checked value="all">All
    <input type="radio" name="filter" <?php if (isset($filter) && $filter=="sport") echo "checked";?> value="sport">Sport
    <input type="radio" name="filter" <?php if (isset($filter) && $filter=="furniture") echo "checked";?> value="furniture">Furniture
    <br>  

<br>
    <button type="submit" class="btn btn-primary">Search</button>

</form>

    <div class="row">
    <?php
    foreach( $query as $q){?>
        <div class="col-md-4">
    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <img src="<?php echo $config['app_url'].'images/'.$q['picture']?>" class="img-thumbnail" alt="...">
            <a class="btn btn-secondary btn-lg" href="editItem.php?id=<?php echo $q['id'] ?>"><?php echo $q['name'] ?></a>
            <p class="card-text"><?php echo $q['description'] ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?php echo "Category: ". $q['category'] ?></li>
            <li class="list-group-item"><?php echo "Price: ".$q['price'].' SAR' ?></li>
        </ul>
    
        </div>
    <br>
    <br></div>
    
<?php
    }

    ?>
    </div>



    <?php require_once __DIR__.'/../template/footer.php'
    
    /*
      else{
            //$category = $_GET['category'];
        if(isset($_GET['search']) && !empty($_GET['search'])){
            echo 'get';
            print_r($_GET);
            $search = $_GET['search'];
            $query = $mysqli->query("select * from item where UPPER(name) like UPPER('%$search%') order by created_at desc");
        }else{
            $query = $mysqli->query("select * from item order by created_at desc");
        }
        echo '<br>';
    }
     */
    ?>