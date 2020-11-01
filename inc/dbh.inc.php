<?php

$servername = "localhost";
$dBUsername = "bethropolis";
$dBPassword = "bethropolis"; 
$dBName = "logtut"; 


$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn){  
   die('connection failed:'.mysqli_connect_error());
}