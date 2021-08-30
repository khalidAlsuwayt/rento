<?php
/*
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE);

curl_setopt($ch, CURLOPT_POST, TRUE);

$fields = <<<EOT
{
  "userName": "khalid18s",
  "numbers": "966590759986",
  "userSender": "rento",
  "apiKey": "3df31048ce198fb55eaaf37957377880",
  "msg": "Pin Code is: 1234"
}
EOT;
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json"
));

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo '<pre>'; var_dump($info["http_code"]);echo '</pre>';
echo '<hr>';
echo '<pre>';var_dump($response);echo '</pre>';
