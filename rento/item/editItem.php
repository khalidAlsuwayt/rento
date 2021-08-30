<?php

require_once __DIR__.'/../config/database.php';

$id = $_GET['id'];
$query = $mysqli->query("select * from item where id = '$id'")->fetch_assoc();

$title = $query['name'];
$errors=[];

require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../template/header.php';

if(!isset($_SESSION['logged'])){ 
    header('location: '.$config['app_url'].'template/notUser.php');
    die();
}
    $name = $query['name'];
    $description = $query['description'];
    $category = $query['category'];
    $price = $query['price'];
    
    print_r($_POST);
    print_r($_FILES['picture']);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = mysqli_real_escape_string($mysqli, $_POST['name']);
        $description = mysqli_real_escape_string($mysqli, $_POST['description']);
        $category = mysqli_real_escape_string($mysqli, $_POST['category']);
        //$picture = mysqli_real_escape_string($mysqli, $_FILES['picture']);
        $price = mysqli_real_escape_string($mysqli, $_POST['price']);
    
        echo $category;
        if(empty($name)){array_push($errors,'Name is Required');}
        if(empty($description)){array_push($errors,'Description is Required');}
        if(empty($category) || $category == 'Choose...'){array_push($errors,'Category is Required');}
        if(empty($_FILES['picture']['name'])){array_push($errors,'Picture is Required');}
        if(empty($price)){array_push($errors,'Price is Required');}

        if(!count($errors)){
            
            echo '1';
            $fileName = time().$_FILES["picture"]['name'];
            $dest = __DIR__.'/../images/'.$fileName;
            echo '2';
            $tmpName = $_FILES['picture']["tmp_name"];
            echo '3';
            move_uploaded_file($tmpName, $dest);
            echo '4';
            $mysqli->query("update item set name = '$name', description = '$description', category = '$category', 
            price = '$price' where id = '$id'");
        
            $_SESSION['msg'] = "The item has been Edited";
    
            header("location: draft.php");
    
        }
    }

?>

<br>
<h1 style="text-align: center;"><?php echo $query['name'];?></h1>
<br>
<hr>

<?php require __DIR__.'/../template/errors.php'?>

<div class="mb-3">
        <br><img id="ima" src="<?php echo $config['app_url'].'images/'.$query['picture'];?>" alt="your image" /><br><br>
    </div>

<form action="" method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label for="name" class="form-label">Name of The Item: </label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo $name ?>">
    </div>
    
    <div class="mb-3">
        <label for="description">Description: </label>
        <textarea name="description" id="description" class="form-control" ><?php echo $description ?></textarea>
    </div>
    <div class="input-group mb-3">
  <label class="input-group-text" for="inputGroupSelect01">Category</label>
  <select class="form-select" id="inputGroupSelect01" name="category">
    <option >Choose...</option>
    <option value="sport" <?php if($category == 'sport'){echo 'selected';}?>>Sport</option>
    <option value="hardware">Hardware</option>
    <option value="electro">Electronics</option>
    <option value="bagsAndClothes">Bags And Clothes</option>
    <option value="furniture">Furniture</option>
    <option value="other">Other</option>
  </select>
</div>
    <div class="mb-3">
        <label for="picture" class="form-label">Upload a Picture: </label>
        <input type="file" onchange="readURL(this);" name="picture" id="picture" class="form-control">
        <br><img id="blah" src="" alt="your image" />
    </div>
    <div class="mb-3">    
        <label for="price">Price per Day</label>
        <input type="number" name="price" id="price" class="form-control" value="<?php echo $price ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>

</form>

<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script>
function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>

<?php require_once __DIR__.'/../template/footer.php'?>