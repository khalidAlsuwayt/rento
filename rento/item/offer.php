<?php

$title = 'Offer an Item';

require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../template/header.php';

if(!isset($_SESSION['logged'])){ 
    header('location: '.$config['app_url'].'template/notUser.php');
    die();
}


$errors = [];

$name = '';
$description = '';
$price = '';
$lat = '';
$lng = '';


//move_uploaded_file($_FILES['picture']["tmp_name"] , __DIR__.'/../images/'.$_FILES["picture"]['name']);


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['draft'])){
        $state = 'draft';
        $isAvailable = 0;
    }
    else{
        $state = 'offer';
        $isAvailable = 1;
    }
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $description = mysqli_real_escape_string($mysqli, $_POST['description']);
    $category = mysqli_real_escape_string($mysqli, $_POST['category']);
    //$picture = mysqli_real_escape_string($mysqli, $_FILES['picture']);
    $price = mysqli_real_escape_string($mysqli, $_POST['price']);
    $lat = mysqli_real_escape_string($mysqli, $_POST['lat']);
    $lng = mysqli_real_escape_string($mysqli, $_POST['lng']);

    if(empty($name)){array_push($errors,'Name is Required');}
    if(empty($description)){array_push($errors,'Description is Required');}
    if(empty($category) || $category == 'Choose...'){array_push($errors,'Category is Required');}
    if(empty($_FILES['picture']['name'])){array_push($errors,'Picture is Required');}
    if(empty($price)){array_push($errors,'Price is Required');}
    if(empty($lat) || empty($lng)){array_push($errors,'Location is Required');}
    //if(empty($password)){array_push($errors,'Password is Required');}

    if(!count($errors)){
            
        $fileName = time().$_FILES["picture"]['name'];
        $dest = __DIR__.'/../images/'.$fileName;
        $tmpName = $_FILES['picture']["tmp_name"];
        move_uploaded_file($tmpName, $dest);
        $userId = $_SESSION['id'];
        $mysqli->query("insert into item (userId, name, description, price, category, picture, isAvailable, lat , lng)
        values ('$userId', '$name','$description','$price','$category','$fileName','$isAvailable','$lat','$lng')");

        $itemId = $mysqli->insert_id;
        $sellerId = $_SESSION['id'];
        echo '5';
        $sellerName = $_SESSION['name'];
        $mysqli->query("insert into itemList (itemId, sellerId, sellerName,state)
        values ('$itemId','$sellerId', '$sellerName','$state')");

        $_SESSION['msg'] = "The item has been Added";

        header("location: ".$config['app_url']);

    }
}


?>

<h1>Offer an item</h1>

<?php require __DIR__.'/../template/errors.php'?>

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
    <option selected>Choose...</option>
    <option value="sport">Sport</option>
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
        <br><img id="blah" src="#" alt="your image" />
    </div>
    <div class="mb-3">    
        <label for="price">Price per Day</label>
        <input type="number" name="price" id="price" class="form-control" value="<?php echo $price ?>">
    </div>
    <div class="container">
        <label for="lat">lat</label>
        <input type="text" id="lat" name="lat" placeholder="Your lat..">

        <label for="lng">lng</label>
        <input type="text" id="lng" name="lng" placeholder="Your lng..">
</div>
    <div class="geocoder">
    <div id="geocoder"></div>
</div>

<div id="map"></div>
<br><br>
    <input type="submit" name="offer" value="Offer" class="btn btn-primary">
    <input type="submit" name="draft" value="Save in The Draft" class="btn btn-primary">

</form>

<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.css' rel='stylesheet' />
<style>
</style>

<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />
<script>
    var user_location = [50.09689187779529,26.398158292263744];
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
    //  geocoder here
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        // limit results to Australia
        //country: 'IN',
    });

    var marker;

    // After the map style has loaded on the page, add a source layer and default
    // styling for a single point.
    map.on('load', function() {
        addMarker(user_location, 'load');

        // Listen for the `result` event from the MapboxGeocoder that is triggered when a user
        // makes a selection and add a symbol that matches the result.
        geocoder.on('result', function(ev) {
            alert("aaaaa");
            console.log(ev.result.center);

        });
    });

    map.on('click', function (e) {
            marker.remove();
            addMarker(e.lngLat,'click');
            //console.log(e.lngLat.lat);
            document.getElementById("lat").value = e.lngLat.lat;
            document.getElementById("lng").value = e.lngLat.lng;

        });

    function addMarker(ltlng, event) {

        if (event === 'click') {
            user_location = ltlng;
        }
        marker = new mapboxgl.Marker({
                draggable: true,
                color: "#d02922"
            })
            .setLngLat(user_location)
            .addTo(map)
            .on('dragend', onDragEnd);
    }

    function onDragEnd() {
        var lngLat = marker.getLngLat();
        document.getElementById("lat").value = lngLat.lat;
        document.getElementById("lng").value = lngLat.lng;
        console.log('lng: ' + lngLat.lng + '<br />lat: ' + lngLat.lat);
    }

    document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

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