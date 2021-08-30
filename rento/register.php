<?php 
$title = 'Registeration';

require_once 'config/app.php';
require_once 'config/database.php';
require_once 'template/header.php';
//session_start();
if(isset($_SESSION['logged'])){
    header("location: ".$config['app_url']."template/alreadyReg.php");
}

?>
<?php 
$errors = [];
$email = '';
$name = '';

print_r($_POST);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);
    $passwordC = mysqli_real_escape_string($mysqli, $_POST['passwordC']);
    $secretKey = "6LdL_i0cAAAAAEiSmbGHJFqVbeN0lypFAuUbZpXi";
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    echo '<hr>';
    echo $userAgent;
    echo '<hr>';
    echo print_r($userIP);

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $response = file_get_contents($url);

    print_r($response);
    $responseKeys = json_decode($response,true);

    if(!$responseKeys['success']){
        array_push($errors,'reCaptcha Failed');
    }

    if(empty($name)){array_push($errors,'Name is Required');}
    if(empty($email)){array_push($errors,'Email is Required');}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {array_push($errors,'Use a Correct Email Format');}
    if(empty($password)){array_push($errors,'Password is Required');}
    if(empty($passwordC)){array_push($errors,'Password Confirmation is Required');}
    if($password != $passwordC){array_push($errors,'Passwords do not match');}


    if(!count($errors)){
        $checkUser = $mysqli->query("select id, email from users where email = '$email'");

        if($checkUser->num_rows){
            array_push($errors, 'Email already exist');
        }else{
            $token = bin2hex(random_bytes(16));
            require_once 'sendEmail.php';

            $mysqli->query("insert into users (name,email,password, token) values ('$name','$email','$password','$token')");
            $_SESSION['logged'] = true;
            $_SESSION['id'] = $mysqli->insert_id;
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['msg'] = "Check your Email For Activation";
            header("location: index.php");
        }


    }


}

?>
        
    <?php echo __DIR__; ?>
    
<h1>Register</h1>

<?php require 'template/errors.php'?>

<form action="" method="post">

    <div class="mb-3">
        <label for="email" class="form-label">Enter your Email: </label>
        <input type="text" name="email" id="email" class="form-control" value="<?php echo $email ?>">
    </div>
    
    <div class="mb-3">
        <label for="name">Enter your Name: </label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo $name ?>">
    </div>
    <div class="mb-3">
        <label for="password">Enter your Password: </label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="mb-3">    
        <label for="passwordC">Confirm your Password: </label>
        <input type="password" name="passwordC" id="passwordC" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
    <div class="g-recaptcha" data-sitekey="6LdL_i0cAAAAAC3qXKePX0S-l1XIXmi9zX1cb5gB"></div>


</form>

<?php require_once 'template/footer.php'?>