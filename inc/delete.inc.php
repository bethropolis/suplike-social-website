<?php 
require 'dbh.inc.php';
header('content-type: application/json'); 

if (isset($_GET['user'])){     
	# delete user
	$sql = "DELETE FROM users WHERE idusers=".$_GET['user'];
	$conn->query($sql); 

   # delete users posts
	$sql = "DELETE FROM posts WHERE userid=".$_GET['user']; 
	$conn->query($sql);

	# delete messages
    $sql = "DELETE FROM likes WHERE user_id=".$_GET['user']; 
	$conn->query($sql);

	# delete followers and follows
    $sql = "DELETE FROM following WHERE user=".$_GET['user']; 
	$conn->query($sql);

	print_r( 
		json_encode(
		array(
			'code'=> 21,
			'message'=> 'user deleted successfuly',
			'status'=> 'successful'
	     )
	 )
 );  
}  
