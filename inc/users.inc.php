<?php
require 'dbh.inc.php'; 
# this is an api that gives the whole of users data on request
# from the database.

if (isset($_GET['key'])) { 
 $key= $_GET['key']; 

 $arr = [];
 $query = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`date_joined`,`last_online` FROM `users` WHERE `idusers`> 0"; 
 $result = $conn->query($query);
 $i =0;  
 while ($row = mysqli_fetch_assoc($result)) { 

    	$arr[$i]= $row;  
    	$i++;   
}

       
 print_r(json_encode($arr));   
header('content-type: application/json'); 



}