<?php 
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'errors/error.inc.php';  
header('content-type: application/json'); 
session_start(); 

if (isset($_POST['delete_profile'])){ 

	if (!isset($_SESSION['userId'])) {
	    //header('Status Code: 403'); 
		die('blue'); 
	}   
	if (!$un_ravel->_isValid($_SESSION['token'])) { 

	   // header('Status Code: 403'); 
		die('red');
	}
	if ($_SESSION['userUid'] !== $_POST['user']) { 
		//header('Status Code: 403'); 
		die('yellow'); 	
	}
	$user = $_SESSION['userId']; 
        
	# delete user
	$sql = "DELETE FROM `users` WHERE `idusers`=".$user;   
	$conn->query($sql); 

   # delete users posts
	$sql = "DELETE FROM `posts` WHERE `userid`=".$user;  
	$conn->query($sql);

	# delete likes
    $sql = "DELETE FROM `likes` WHERE `user_id`=".$user; 
	$conn->query($sql);

	# delete followers and follows
    $sql = "DELETEFROM `following` WHERE `following`.`user`=".$user." OR `following`.`following`=".$user;  
	$conn->query($sql);
    # delete messages 
    $sql = "DELETE FROM `chat` WHERE `chat`.`who_from` =".$user."OR `chat`.`who_to`=".$user;     
	$conn->query($sql);
    # delete messages 
    $sql = "DELETE FROM `auth_key` WHERE `auth_key`.`user`=".$user;     
	$conn->query($sql);
	

	header('Location: ../inc/logout.inc.php');    
}else{  
	$err = new Err(15); 
    $err->err($u,null,'account could not be deleted');  
 	die();     
} 
