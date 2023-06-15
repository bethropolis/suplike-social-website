<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/notification.class.php';
header('Content-Type: application/json');
session_start();

// a wrapper for 'extra/notification.class.php';

if(!isset($_SESSION['userId'])){
 die(json_encode([
    'type'=>'error',
    'message'=> 'not authenticated',
 ]));
}

$user = $_SESSION['userId'];
$notify = new Notification();
if(isset($_GET['fetch'])){
    $notify->_get_notify($user);
}
if(isset($_GET['fetch_seen'])){
    $notify->_get_seen($user);
}
if(isset($_GET['delete'])){
    $notify->_delete_notify($_GET['delete']);
}
if(isset($_GET['seen'])){
    $notify->_update_seen($_GET['seen']);
}
if(isset($_GET['seenall'])){
    $notify->_update_seenall($user);
}
if(isset($_GET['notify'])){
    $text = $_POST['text'];
    $type = $_POST['type']; 
    $notify->notify($user,$text,$type);
}
if(isset($_GET['delete_all'])){
    $notify->_delete_all($user);
}

if(isset($_GET['seen_all'])){
    $notify->_update_seenall($user);
}

if(isset($_GET['type'])){
    $notify->_get_type($_GET['type']);
}

// if isset $_GET['new'] then check notifications sent in the last 30 seconds return true if there are any
if(isset($_GET['new'])){
    $notify->_check_new($user);
} 