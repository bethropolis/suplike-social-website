<?php
include_once './dbh.inc.php';
include_once  './Auth/auth.php';
if (isset($_POST['id'])) {
    $comment = $_POST['comment'];
    $post = $_POST['id']; 
    $user = $_POST['user']; 
    $sql ="SELECT `idusers` FROM `users` WHERE `uidusers`='$user'";  
    $token = $un_ravel->_queryUser(((mysqli_fetch_assoc($conn->query($sql)))['idusers']),1);   
    
    $sql = "INSERT INTO `comments`(`comment_id`,`user`,`user_token`,`comment`) VALUES (?,?,?,?)";  
    $stmt = $conn->prepare($sql);    
    $stmt->bind_param("ssss", $post ,$user, $token, $comment);           
    $stmt->execute(); 
    print_r(json_encode('commented')); 
}





