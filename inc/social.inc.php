<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
header('content-type: application/json');
session_start();
//auth check
$un_ravel->_isAuth();


// get people the user follows from `following` table and from `chat` table get last message between them
if (isset($_GET['user'])) {
    $user = $un_ravel->_getUser($_GET['user']);
    $answer = [];
    $query = "SELECT * FROM `following` WHERE `user`=$user";
    $result = $conn->query($query);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $f = $row['following'];
        $query = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`last_online`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers` = $f AND `auth_key`.`user` ='$f' ";
        $row = $conn->query($query)->fetch_assoc();
        $answer['users'][$i] = $row;
        $user_id = $row['idusers'];
        $last_online = $row['last_online'];
        $answer['users'][$i]['full_name'] = $row['usersFirstname'] . " " . $row['usersSecondname'];
        // if full name = null then set it to username
        if ($answer['users'][$i]['full_name'] == " ") {
            $answer['users'][$i]['full_name'] = $row['uidusers'];
        }
        $sql = "SELECT `message`,`time`,`type` FROM `chat` WHERE (`who_to`='$user_id' OR `who_to`='$user') AND (`who_from`='$user_id' OR `who_from`='$user') ORDER BY `chat`.`time` DESC LIMIT 1";
        $r = $conn->query($sql)->fetch_assoc();
        //  format time into 12 hour format hh:mm am/pm and if it is today or yesterday or a date
        if (!is_null($r)) {
            $date = new DateTime($r['time']);
            $answer['users'][$i]['day'] = $date->format('l');
            $answer['users'][$i]['last_msg'] = $r['message'];
            $answer['users'][$i]['time'] = $date->format('G:i a');
            $answer['users'][$i]['actual_time'] = $r['time'];
            $answer['users'][$i]['type'] = $r['type'];
        } else {
            $answer['users'][$i]['last_msg'] = "";
            $answer['users'][$i]['type'] = "";

        }
        $date = new DateTime('now');
        $date->format("Y-m-d H:i:s");
        $date = $date->modify("-7 minutes");
        $date = $date->format('Y-m-d H:i:s');
        if ($last_online > $date) {
            $answer['users'][$i]['online'] = true;
        } else {
            $answer['users'][$i]['online'] = false;
        }

        $i++;
    }


    // Sort the array based on last_online in descending order
    usort($answer['users'], function ($a, $b) {
        if (isset($a['last_online']) && isset($b['last_online'])) {
            return strtotime($b['last_online']) - strtotime($a['last_online']);
        } elseif (isset($a['last_online'])) {
            return -1; // Place the item with last_online first
        } elseif (isset($b['last_online'])) {
            return 1; // Place the item with last_online first
        } else {
            return 0; // Keep the order unchanged
        }
    });

    // Sort the array based on actual_time in descending order
    usort($answer['users'], function ($a, $b) {
        if (isset($a['actual_time']) && isset($b['actual_time'])) {
            return strtotime($b['actual_time']) - strtotime($a['actual_time']);
        } elseif (isset($a['actual_time'])) {
            return -1; // Place the item with actual_time first
        } elseif (isset($b['actual_time'])) {
            return 1; // Place the item with actual_time first
        } else {
            return 0; // Keep the order unchanged
        }
    });
    print_r(json_encode($answer));
}

die();

if (isset($_GET['user'])) {
    $user = $un_ravel->_getUser($_GET['user']);
    $arr = [];
    $query = "SELECT * FROM `following` WHERE `user`=$user";
    $result = $conn->query($query);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $f = $row['following'];
        $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`last_online`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers`=$f AND `auth_key`.`user` = $f ";
        $resp = $conn->query($sql)->fetch_assoc();
        $newTime = strtotime('-15 minutes');
        $t = date('Y-m-d H:i:s', $newTime);
        if (date($resp['last_online']) > date($t)) {
            $resp["online"] = true;
        } else {
            $resp["online"] = false;
        }
        $arr[$i] = $resp;
        $i++;
    }


    print_r(json_encode($arr));
}