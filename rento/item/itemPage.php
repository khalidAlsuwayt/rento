<?php

require_once __DIR__.'/../config/database.php';

$id = $_GET['id'];

$query = $mysqli->query("select * from item where id = '$id'")->fetch_assoc();

//SELECT (SUM(score) / COUNT(score) ) FROM `rating` WHERE sellerId = 11
$sellerId = $query['userId'];
$rate = $mysqli->query("SELECT (SUM(score) / COUNT(score) ) rate FROM `rating` WHERE sellerId = '$sellerId'")->fetch_assoc();

$title = $query['name'];
$lat = $query['lat'];
$lng = $query['lng'];
$errors=[];
require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../template/header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $from = mysqli_real_escape_string($mysqli, $_POST['from']);
    $to = mysqli_real_escape_string($mysqli, $_POST['to']);

    if(empty($from)){array_push($errors,'from is Required');}
    if(empty($to)){array_push($errors,'to is Required');}
    if($from>=$to){array_push($errors,"Date From Must be Before Date To");}

    if(!count($errors)){
        $userId = $_SESSION['id'];
        $userName = $_SESSION['name'];
        $mysqli->query("update itemList set fromDate = '$from', toDate = '$to', state = 'rentRequest', 
        buyerId = '$userId', buyerName = '$userName' where itemId = '$id'  ");
        $_SESSION['msg'] = "The Request has Been Sent";

        header("location: ".$config['app_url']);
    }
}

//if(!isset($_SESSION['logged'])){header('location: '.$config['app_url'].'template/notUser.php');    die();}
?>
<br>
<h1 style="text-align: center;"><?php echo $query['name'];?></h1>
<br>
<hr>
<?php require __DIR__.'/../template/errors.php'?>


<br>

<img src="<?php echo $config['app_url'].'images/'.$query['picture']?>"  style="max-width:800px;" alt="...">
<br>
<br>

<h3>Description:</h3><hr>
<p><?php echo $query['description'];?></p>

<h3>Seller</h3> <hr>

<?php 

$seller = $mysqli->query("select name from users where id='$sellerId'")->fetch_assoc();
?>

Name : <a style="text-decoration: none;" href="<?php echo $config['app_url'].'profile/index.php?id='.$query['userId'] ?>"> <?php echo $seller['name']; ?> </a>
<br>
Rating : <?php echo round($rate['rate'], 2).'/5' ;?>

<br>
<br>

<h3>Location</h3><hr>




<div id="map"></div>

<br><br>
<?php 

if(isset($_SESSION['logged'])){ 

    $check = $mysqli->query("select buyerId from itemList where itemId = '$id'")->fetch_assoc();

    if($_SESSION['id'] == $query['userId']){
        echo '<h4>You are The owner of This Item</h4>';
    }
    else if($check['buyerId'] == $_SESSION['id']){
        echo '<h4>You Have already sent a Request</h4>';
    }else{?>
        <form action="" method="post" enctype="multipart/form-data">
        From    <input type="date" name="from" value="From"> <br><br>
        To      <input type="date" name="to" value="To"> <br><br>
        <input type="submit" name="rent" value="Rent" class="btn btn-primary">
        </form>
    <?php }
}else{
    echo 'ghfds';
    //header('location: '.$config['app_url'].'template/notUser.php');
    //die();
}

?>
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.css' rel='stylesheet' />
<style>
</style>

<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />

<script>

var user_location = [<?php echo $lng?>, <?php echo $lat?>];
    mapboxgl.accessToken = 'pk.eyJ1IjoiZmFraHJhd3kiLCJhIjoiY2pscWs4OTNrMmd5ZTNra21iZmRvdTFkOCJ9.15TZ2NtGk_AtUvLd27-8xA';
    mapboxgl.setRTLTextPlugin(
        'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.3/mapbox-gl-rtl-text.js',
        null,
        true // Lazy load the plugin
    );
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v9',
        center: user_location,
        zoom: 12
    });
    map.addControl(
        new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            // When active the map will receive updates to the device's location as it changes.
            trackUserLocation: true,
            // Draw an arrow next to the location dot to indicate which direction the device is heading.
            showUserHeading: true
        })
    );

    map.on('load', function() {
        addMarker(user_location, 'load');

        // Listen for the `result` event from the MapboxGeocoder that is triggered when a user
        // makes a selection and add a symbol that matches the result.
        geocoder.on('result', function(ev) {
            alert("aaaaa");
            console.log(ev.result.center);

        });
    });

    function addMarker(ltlng, event) {

        marker = new mapboxgl.Marker({
                //draggable: true,
                color: "#d02922"
            })
            .setLngLat(user_location)
            .addTo(map)
            .on('dragend', onDragEnd);
    }

</script>
<?php require_once __DIR__.'/../template/footer.php'?>