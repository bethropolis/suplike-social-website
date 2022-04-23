<?php 
require '../dbh.inc.php';
require 'auth.php';
require 'email.php';
require '../extra/notification.class.php';
$notification = new Notification();


if(isset($_GET['id'])){
 $email_id = $un_ravel->_getUser($_GET['id']);
 if($email_id){
     $idUsers = $email_id;
    //  update users table set email_verified = 1 where id = $idUsers
    $sql = "UPDATE `users` SET `email_verified` = 1 WHERE `idusers` = '$idUsers'";
    $conn->query($sql);
    $notification->notify($idUsers, "Your email has been verified");
 }
}