<?php

// Create database connection to database
 require 'dbh.inc.php';  
    
 // Initialize message variable 
 // If upload button is clicked ...
 if (isset($_POST['upload'])) { 
 session_start();

 // mentioning all my variables that I will use
$image = $_FILES['image']['name'];
$file_size =$_FILES['image']['size'];
$file_tmp =$_FILES['image']['tmp_name'];
$file_type=$_FILES['image']['type'];
$dot = explode('.', $_FILES['image']['name']);
$file_ext=strtolower(end($dot));  
$extensions= array("jpeg","jpg","png","webp", ""); //the empty one is for text post without <image></image> 
$image_text = mysqli_real_escape_string($conn, $_POST['posttext']); 
$time = $_POST['time_of_post'].".".$file_ext;   
$postTime = $_POST['time_posted'];  
$user = $_SESSION['userId']; 

if(in_array($file_ext, $extensions)){   
if($file_size < 6291456){  
    $target = "../img/".$time;     
    $sql = "INSERT INTO posts (image, image_text, userid, date_posted, date_of_upload) VALUES (?, ?, ?, ?, ?)";
     move_uploaded_file($file_tmp, $target);            
     $stmt= $conn->prepare($sql);
     $stmt->bind_param("sssss", $image, $image_text, $user, $postTime, $time);
     $stmt->execute(); 
  
     header("Location: ../index.php?upload=success");     
    
  }else{
  	header('Location: ../index.php?upload=filetobig') ;
  } 
 }else{
 	header('Location: ../index.php?upload=extnotallowed') ;
 }
}
 $result = mysqli_query($conn, "SELECT * FROM `posts` ORDER BY `posts`.`id` DESC"); 
