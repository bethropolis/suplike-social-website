<?php
header('content-type: application/json');
include_once '../dbh.inc.php';
require '../Auth/auth.php';
session_start();
$un_ravel->_isAuth();

if(!$un_ravel->_isAdmin($_SESSION['userId'])){
	header('HTTP/1.1 403 Forbidden');
}
$arr = [];

$dt = new DateTime("now", new DateTimeZone('Africa/Nairobi'));
$dt->format("Y-m-d H:i:s");
$dt = $dt->modify("-7 days");
$dt = $dt->format('Y-m-d H:i:s');

// likes
try {
    $sql = "SELECT `id`,`time`  FROM `likes` WHERE `time` > '$dt'";
    $result = $conn->query($sql);

    if ($result === false) {
        // handle error gracefully
        $arr['likes'] = [];
    } else {
        while ($row = $result->fetch_assoc()) {
            $arr['likes'][] = $row;
        }
    }
} catch (Exception $e) {
    // handle exception gracefully
    $arr['likes'] = [];
}

// check if the key exists before accessing it
if(!array_key_exists('likes', $arr) || !$arr['likes']){
    $arr['likes'] = [];
}

// users
try {
    $sql = "SELECT `idusers`,`date_joined`  FROM `users` WHERE `date_joined` > '$dt'";
    $result = $conn->query($sql);

    if ($result === false) {
        // handle error gracefully
        $arr['users'] = [];
    } else {
        while ($row = $result->fetch_assoc()) {
            $arr['users'][] = $row;
        }
    }
} catch (Exception $e) {
    // handle exception gracefully
    $arr['users'] = [];
}

// check if the key exists before accessing it
if(!array_key_exists('users', $arr) || !$arr['users']){
    $arr['users'] = [];
}

// following
try {
    $sql = "SELECT `id`,`time`  FROM `following` WHERE `time` > '$dt'";
    $result = $conn->query($sql);

    if ($result === false) {
        // handle error gracefully
        $arr['following'] = [];
    } else {
        while ($row = $result->fetch_assoc()) {
            $arr['following'][] = $row;
        }
    }
} catch (Exception $e) {
    // handle exception gracefully
    $arr['following'] = [];
}

// check if the key exists before accessing it
if(!array_key_exists('following', $arr) || !$arr['following']){
    $arr['following'] = [];
}

// posts
try {
    $sql = "SELECT `id`,`time` FROM `posts` WHERE `time` > '$dt'";
    $result = $conn->query($sql);

    if ($result === false) {
        // handle error gracefully
        $arr['posts'] = [];
    } else {
        while ($row = $result->fetch_assoc()) {
            $arr['posts'][] = $row;
        }
    }
} catch (Exception $e) {
    // handle exception gracefully
    $arr['posts'] = [];
}

// check if the key exists before accessing it
if(!array_key_exists('posts', $arr) || !$arr['posts']){
    $arr['posts'] = [];
}

// chat
try {
    $sql = "SELECT `id`,`time` FROM `chat` WHERE `time` > '$dt'";
    $result = $conn->query($sql);
    
    if ($result === false) {
    // handle error gracefully
    $arr['chat'] = [];
} else {
    while ($row = $result->fetch_assoc()) {
        $arr['chat'][] = $row;
    }
}
} catch (Exception $e) { 
    // handle exception gracefully 
    $arr['chat'] = [];
    }

// check if the key exists before accessing it 
if(!array_key_exists('chat', $arr) || !$arr['chat']){
    $arr['chat'] = [];
    }


// shares
try {
    $sql = "SELECT `id`,`time` FROM `share` WHERE `time` > '$dt'";
    $result = $conn->query($sql);

    if ($result === false) {
        // handle error gracefully
        $arr['share'] = [];
    } else {
        while ($row = $result->fetch_assoc()) {
            $arr['share'][] = $row;
        }
    }
} catch (Exception $e) {
    // handle exception gracefully
    $arr['share'] = [];
}

// check if the key exists before accessing it
if(!array_key_exists('share', $arr) || !$arr['share']){
    $arr['share'] = [];
}

// comments
try {
    $sql = "SELECT `id`,`date` FROM `comments` WHERE `date` > '$dt'";
    $result = $conn->query($sql);

    if ($result === false) {
        // handle error gracefully
        $arr['comments'] = [];
    } else {
        while ($row = $result->fetch_assoc()) {
            $arr['comments'][] = $row;
        }
    }
} catch (Exception $e) {
    // handle exception gracefully
    $arr['comments'] = [];
}


if(!array_key_exists('comments', $arr) || !$arr['comments']){
    $arr['comments'] = [];
}



$arr['time'] = $dt;
print_r(json_encode($arr));
