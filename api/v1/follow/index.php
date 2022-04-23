<?php
require '../r.php';
// follow or unfollow a user
// Path: api\v1\follow\index.php
// Compare this snippet from ../../../inc/follow.inc.php
if(!isset($_GET['user'])){
    die(json_encode(['error'=>'no user specified']));
}
    $user = $un_ravel->_getUser($_GET['user']);
    $arr = [];
    $query = "SELECT * FROM `following` WHERE `user`=$user";
    $result = $conn->query($query);
    $i = 0;
    $pmi = 1;
    $pfi = 1;

    while ($row = mysqli_fetch_assoc($result)) {

        $f = $row['following'];
        $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`gender`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers`=$f AND `auth_key`.`user` = $f ";
        $resp = $conn->query($sql)->fetch_assoc();
        $resp['full_name'] = '' . $resp["usersFirstname"] . ' ' . $resp["usersSecondname"];
        $to = $resp['idusers'];
        if ($resp["gender"] == 'M') {
            $resp['profile_picture'] = 'm' . $pmi;
            $pmi++;
        } else {
            $resp['profile_picture'] = 'f' . $pfi;
            $pfi++;
        }
        $from = $user;
        $lastMsg = $conn->query("SELECT `message`,`time` FROM `chat` WHERE (`who_to`='$to' OR `who_to`='$from') AND (`who_from`='$to' OR `who_from`='$from') ORDER BY `chat`.`time` DESC LIMIT 1");

        if ($work = mysqli_fetch_assoc($lastMsg)) {
            $resp["last_msg"] = $work["message"];
            $date = new DateTime($work["time"]);
            $resp["time"] = $date->format('G:i a');
            $date = new DateTime('now');
            $date->format("Y-m-d H:i:s");
            $date = $date->modify("-7 minutes");
            $date = $date->format('Y-m-d H:i:s');
            $query = $conn->query("SELECT `idusers`, `gender`,`last_online` FROM `users` WHERE `idusers`='$to' AND `last_online`>'$date'");
            if (mysqli_fetch_assoc($query)) {
                $resp["online"] = true;
            } else {
                $resp["online"] = false;
            }
            $arr[$i] = $resp;
        } else {
            $resp["last_msg"] = null;
            $resp["time"] = null;
        }
        #      $arr[$i] = $resp;
        $i++;
    }
    print_r(json_encode($arr));
