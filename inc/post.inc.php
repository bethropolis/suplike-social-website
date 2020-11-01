<?php

// Create database connection to bethro test database
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
$extensions= array("jpeg","jpg","png", "");  
$image_text = mysqli_real_escape_string($conn, $_POST['posttext']); 
$time = $_POST['time_of_post'].".".$file_ext;   
$postTime = $_POST['time_posted'];  
$user = $_SESSION['userId']; 

if(in_array($file_ext, $extensions)){   
if($file_size < 6291456){  
    $target = "../img/".$time;    
    $sql = "INSERT INTO images (image, image_text, userid, date_posted, date_of_upload) VALUES ('$image', '$image_text', '$user', '$postTime', '$time')";
     move_uploaded_file($file_tmp, $target);            
     mysqli_query($conn, $sql);  
     header("Location: ../index.php?upload=success");     
    
  }else{
  	header('Location: ../index.php?upload=filetobig') ;
  } 
 }else{
 	header('Location: ../index.php?upload=extnotallowed') ;
 }
}
 $result = mysqli_query($conn, "SELECT * FROM `images` ORDER BY `images`.`id` DESC"); 
 $result2 = mysqli_query($conn, "SELECT * FROM `likes`");