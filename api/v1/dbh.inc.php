<?php
require_once "env.php";
$servername = DB_HOST;
$dBUsername = DB_USERNAME;
$dBPassword = DB_PASSWORD;
$dBName = DB_DATABASE;
$dBPort = DB_PORT;
$timeZone = new DateTimeZone('Africa/Nairobi');

try {
  $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName,$dBPort) or die("Connection failed");
  $conn->set_charset('utf8mb4');
} catch (\Throwable $th) {
     // set 500 error status code
      http_response_code(500);
      exit();
 }
?>
