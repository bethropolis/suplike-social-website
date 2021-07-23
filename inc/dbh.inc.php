<?php
$servername = "localhost";
$dBUsername = "root";
$dBPassword = ""; 
$dBName = "suplike";
$timeZone = new DateTimeZone('Africa/Nairobi'); 

try {
$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
if (!$conn){  
   $isSetup = file_get_contents('');
  header('Status Code: 500');
} 

$conn->set_charset('utf8mb4');
} catch (\Throwable $th) {
  echo "error"; 
} 