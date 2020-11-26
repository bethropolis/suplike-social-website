<?php
require 'dbh.inc.php'; 
# this is an api that gives the whole of users data on request
# from the database.



$answer = array();
$id = isset($_GET['id'])? $_GET['id']: null; 

if(!empty($id)){
$query = "SELECT * FROM `users` WHERE `idusers`='$id'"; 
$answer['user'] = $conn->query($query)->fetch_assoc();
$query = "SELECT * FROM `posts` WHERE `userid`='$id'";   
$result = $conn->query($query); 
$i = 0;
while ($row = mysqli_fetch_assoc($result)) { 
	$answer['posts'][$i] = $row; 
	$i++;                  
} 
print_r(json_encode($answer));  


}

header('content-type: application/json');
