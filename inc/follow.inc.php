<?php 
require 'dbh.inc.php';  
header('content-type: application/json');

# this was a 100% copy from like.inc.php,
# I will consider merging them in future
if(isset($_GET['following'])){

if (empty($_GET['user'])||empty($_GET['key'])) { 
	 	print_r(
 		json_encode(
         array( 
           'code' => 32,  
           'message'=> 'missing parameters in request'           
 		)  
     )
     
 	); 
	 	die(); 
}
 
$following = $_GET['user'];  
$followed = $_GET['following'];  
$key = $_GET['key']; 

if (!($key == 'false'||$key == 'true')){  
	print_r(
 		json_encode(
         array( 
           'code' => 32,  
           'message'=> 'key parameter should be boolean'              
 		)  
     )
     
 	); 

die();  
}

if ($following == $followed) {
   print_r(
 		json_encode(
         array( 
           'code' => 35,  
           'message'=> 'cannot follow yourself'              
 		)  
     )
     
 	); 

die();  	
}

if(!is_numeric($followed)||!is_numeric($following)){
	 	print_r(
 		json_encode(
         array( 
           'code' => 32,  
           'message'=> 'invalid value in parameter'             
 		)  
     )
     
 	); 
 	die(); 	
} 

$sql = "SELECT * FROM `users` WHERE `idusers`='$following'"; 
$result = $conn->query($sql)->fetch_assoc();  
if (is_null($result)) {
 	print_r(
 		json_encode(
         array( 
           'code' => 33,
           'message'=> 'user does not exist'          
 		)  
     )

 	);  
 	die();  
 }else{
 	$following_user_follows = $result['following'];
 }


$sql = "SELECT * FROM `users` WHERE `idusers`='$followed'"; 
$result = $conn->query($sql)->fetch_assoc();  
if (is_null($result)) {
 	print_r(
 		json_encode(
         array( 
           'code' => 33,
           'message'=> 'user does not exist'          
 		)  
     )

 	);  
 	die();  
 }else{
 	$followed_user_followers= $result['followers']; 

 } 



$sql = "SELECT * FROM `following` WHERE `user`='$following' AND `following`='$followed'"; 
$result = $conn->query($sql)->fetch_assoc();



if (is_null($result)&&$key == 'true') {  
    $following_user_follows = $following_user_follows + 1;
    $followed_user_followers = $followed_user_followers + 1;
    $sql  = "INSERT INTO following (`user`,`following`) VALUES ($following, $followed)" ;
    $conn->query($sql);  
 }

if (!is_null($result)&&$key =='false') {
    $following_user_follows = $following_user_follows - 1; 
    $followed_user_followers = $followed_user_followers - 1;  
    $sql  = "DELETE FROM `following` WHERE `user` = '$following' AND `following`='$followed'" ;
    $conn->query($sql);    
 } 

if (!is_null($result)&&$key == 'true') {
 	print_r(
 		json_encode( 
         array( 
           'code' => 34,
           'message'=> 'already followed this user'          
 		)  
     )

 	); 
 	die();  
 }  

if (is_null($result)&&$key == 'false') {
 	print_r(
 		json_encode(
         array( 
           'code' => 33, 
           'message'=> 'user does not exist'          
 		)  
     )

 	); 
 	die();  
 }



$sql = "UPDATE `users` SET `followers` = ' $followed_user_followers' WHERE `idusers` = '$followed';"; 
mysqli_query($conn, $sql);

$sql = "UPDATE `users` SET `following` = '$following_user_follows' WHERE `idusers` = '$following';"; 
mysqli_query($conn, $sql);

# actually I don't think we will be merging them again; 
print_r(
	json_encode(
       array(
       	'code' => 21, 
       	'post' => 'successful'
         
       )   
	)
);     
}




