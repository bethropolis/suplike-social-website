<?php 
require 'dbh.inc.php';  
header('content-type: application/json');

if(isset($_GET['id'])){

if (empty($_GET['user'])||empty($_GET['like'])||empty($_GET['key'])) { 
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


$user = $_GET['user'];  
$post = $_GET['id'];  
$like = $_GET['like'];
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

if(!is_numeric($post)||!is_numeric($user)||!is_numeric($like)){
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

$sql = "SELECT * FROM `users` WHERE `idusers`='$user'"; 
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
 } 

$sql = "SELECT * FROM `posts` WHERE `posts`.`id`='$post'"; 
$result = $conn->query($sql)->fetch_assoc(); 
if (is_null($result)) {
 	print_r(
 		json_encode(
         array( 
           'code' => 33,
           'message'=> 'post does not exist'          
 		)  
     )

 	); 
 	die();  
 } 

if (($result['post_likes'] - $like) > 1||($result['post_likes']-$like) < -1) { 
   	print_r(
 		json_encode(
         array( 
           'code' => 35,
           'message'=> 'illigal action performed'           
 		)  
     )

 	);
 	die();          	
  }         

$sql = "SELECT * FROM `likes` WHERE `user_id`='$user' AND `post_id`='$post'"; 
$result = $conn->query($sql)->fetch_assoc();



if (is_null($result)&&$key == 'true') {  
    $sql  = "INSERT INTO likes (`user_id`,`post_id`) VALUES ($user, $post)" ;
    $conn->query($sql);  
 }

if (!is_null($result)&&$key =='false') {  
    $sql  = "DELETE FROM `likes` WHERE `user_id` = '$user' AND `post_id`='$post'" ;
    $conn->query($sql);    
 } 

if (!is_null($result)&&$key == 'true') {
 	print_r(
 		json_encode( 
         array( 
           'code' => 34,
           'message'=> 'already liked this'          
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
           'message'=> 'post does not exist'          
 		)  
     )

 	); 
 	die();  
 }



$sql = "UPDATE `posts` SET `post_likes` = '$like' WHERE `posts`.`id` = '$post';"; 
mysqli_query($conn, $sql); 
print_r(
	json_encode(
       array(
       	'code' => 21, 
        'successful'
         
       )   
	)
);     
}

