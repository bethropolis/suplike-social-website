<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
header('content-type: application/json');
// get people the user follows from `following` table and from `chat` table get last message between them
if (isset($_GET['user'])) {
    $user =  $un_ravel->_getUser($_GET['user']);
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
        $sql = "SELECT `message`,`time`,`type` FROM `chat` WHERE (`who_to`='$user_id' OR `who_to`='$user') AND (`who_from`='$user_id' OR `who_from`='$user') ORDER BY `chat`.`time` DESC LIMIT 1";
        $r = $conn->query($sql)->fetch_assoc();
        //  format time into 12 hour format hh:mm am/pm and if it is today or yesterday or a date
        if (!is_null($r)) {
            $date = new DateTime($r['time']);
            $answer['users'][$i]['day'] = $date->format('l');
            $answer['users'][$i]['last_msg'] = $r['message'];
            $answer['users'][$i]['time'] =  $date->format('G:i a');
            $answer['users'][$i]['actual_time'] = $r['time'];
            $answer['users'][$i]['type'] = $r['type'];
        } else {
            $answer['users'][$i]['last_msg'] = "";
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
