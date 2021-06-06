<?php
$servername = "mysql-bethro.alwaysdata.net";
$dBUsername = "bethro";
$dBPassword = "cQ2Tn0jUj2SS"; 
$dBName = "bethro_suplike";
$timeZone = new DateTimeZone('Africa/Nairobi');   


$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

$conn->set_charset('utf8mb4');

if (!$conn){  
   die('connection failed:'.mysqli_connect_error());
} 