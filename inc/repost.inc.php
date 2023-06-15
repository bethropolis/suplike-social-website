<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
session_start();

//auth check
$un_ravel->_isAuth();

$id = $_GET['id'];
$user = $_SESSION['userId'];

$sql = "SELECT * FROM `posts` WHERE `post_id` = '$id'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if (empty($row)) {
    header('Location: ../?error=notexist');
    exit();
}

if ($row['userid'] == $user) {
    header('Location: ../?error=yrpost'); 
    exit();
}

$sql = "SELECT `id` FROM `posts` WHERE `repost` = '$id' AND `userid`='$user'"; 
$result = $conn->query($sql);

if(!empty(mysqli_fetch_assoc($result))){ 
    header('Location: ../?error=reposting'); 
     exit(); 
}

$d = new DateTime('now', $timeZone);
$date = $d->format('j M');
$day = $d->format('l');
$image = $row['image'];
$image_text = $row['image_text'];
$bin =bin2hex(openssl_random_pseudo_bytes(4));

if($row['type'] == 'txt'){
    $type = "txt";
    $sql = "INSERT INTO posts (`post_id`,`image_text`, `userid`,`repost`,`type` ,`date_posted`, `day`) VALUES ('$bin', '$image_text', $user,'$id', '$type', '$date', '$day')";
    $conn->query($sql); 

}

if($row['type'] == 'img'){ 
    $type = "img";
    $sql = "INSERT INTO posts (`post_id`,`image`, `image_text`,`repost`, `type`, `userid`, `date_posted`, `day`) VALUES ('$bin','$image','$image_text','$id','$type',$user,'$date','$day')";
    $conn->query($sql); 
    print_r($sql);
}


header('Location: ../?success=reposted');  
 exit(); 

