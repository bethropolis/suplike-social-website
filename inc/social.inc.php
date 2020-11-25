<?php 
require 'dbh.inc.php';

if (isset($_GET['user'])) {
 $user = $_GET['user']; 

 $arr = [];
 $query = "SELECT * FROM `following` WHERE `user`=$user"; 
 $result = $conn->query($query);
 $i =0; 
 while ($row = mysqli_fetch_assoc($result)) {
    $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname` FROM `users` WHERE `idusers`=".$row['following'];       
    $response = $conn->query($sql);
    while ($resp = mysqli_fetch_assoc($response)) {
    	$arr['following'][$i]= $resp; 
    	$i++; 
    }
    
}

       
 print_r(json_encode($arr));   
header('content-type: application/json'); 



}