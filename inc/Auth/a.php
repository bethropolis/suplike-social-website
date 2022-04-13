<?php
require '../dbh.inc.php';
require '../Auth/auth.php';
header("Content-Type: application/json");
session_start();

if(!isset($_SESSION['userId'])) {
    die(json_encode(
        [
            'code' => 4,
            'msg' => "You are not logged in",
            'type' => 'error'
        ]
      ));
    }
// cannot change token if an hour has not passed
$id = $_SESSION['userId'];
$current_time = time();
$subtract_an_hour = $current_time - 3600;
$time = date('Y-m-d H:i:s', $subtract_an_hour);
$sql = "SELECT * FROM `api` WHERE `user` = $id AND `date` > '$time'";
if(mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
    die(json_encode(
        [
            'code' => 4,
            'msg' => "You can only change your token once an hour",
            'type' => 'error'
        ]
    ));
}

$token = bin2hex(random_bytes(32));
try{
// check if token already exists else update
$sql = "SELECT * FROM `api` WHERE `user` = $id";
if(mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
    $sql = "UPDATE `api` SET `key` = '$token', `date` = NOW() WHERE `user` = $id";
} else {
    $sql = "INSERT INTO `api` (`user`, `key`, `date`) VALUES ($id, '$token', NOW())";
}
mysqli_query($conn, $sql);
} catch(Exception $e) {
    die(json_encode(
        [
            'code' => 4,
            'msg' => "Something went wrong",
            'type' => 'error'
        ]
    ));
}

die(json_encode(
    [
        'code' => 1,
        'msg' => "Success",
        'token' => $token
    ]
));