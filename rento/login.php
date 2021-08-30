<?php 
$title = 'Log In';

require_once 'config/app.php';
require_once 'config/database.php';
require_once 'template/header.php';

if(isset($_SESSION['logged'])){
    header("location: template/alreadyLogged.php");
}

?>
<?php 
$errors = [];
$email = '';
$name = '';



if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //$name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    if(empty($email)){array_push($errors,'Email is Required');}
    if(empty($password)){array_push($errors,'Password is Required');}


    if(!count($errors)){
        $checkUser = $mysqli->query("select id,name,password, email from users where email = '$email'")->fetch_assoc();

        if(!$checkUser){
            array_push($errors, "User doesn't exist");
        }else{
            if($password == $checkUser['password']){
                //$mysqli->query("insert into users (name,email,password) values ('$name','$email','$password')");
                $_SESSION['logged'] = true;
                $_SESSION['id'] = $checkUser['id'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $checkUser['name'];
                $_SESSION['msg'] = "Welcome Back ".$checkUser['name'];
                setcookie('log', $_SESSION['id'], time() + 60*60);
                //echo $_COOKIE['test'];
                header("location: index.php");
            }else{
                array_push($errors, "Password is incorrect");
            }       
        }
    }
}

?>
        
    
<h1>Log In</h1>

<?php require 'template/errors.php'?>

<form action="" method="post">

    <div class="mb-3">
        <label for="email" class="form-label">Enter your Email: </label>
        <input type="text" name="email" id="email" class="form-control" value="<?php echo $email ?>">
    </div>
    <div class="mb-3">
        <label for="password">Enter your Password: </label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Log In</button>

</form>

<?php require_once 'template/footer.php'?>