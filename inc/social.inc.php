<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
header('content-type: application/json');

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
        if (date($resp['last_online']) > date($t)){
            $resp["online"] = true;
        }else{
            $resp["online"] = false;
        }
        $arr[$i] = $resp;
        $i++;
    }


    print_r(json_encode($arr));
}
