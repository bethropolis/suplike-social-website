<?php
if (isset($_GET['following'])) {
    $user = $un_ravel->_getUser($_GET['user']);
    $arr = [];
    $query = "SELECT * FROM `following` WHERE `user`=$user";
    $result = $conn->query($query);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $f = $row['following'];
        $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers`=$f AND `auth_key`.`user` = $f ";
        $resp = $conn->query($sql)->fetch_assoc();
        $resp['full_name'] = '' . $resp["usersFirstname"] . ' ' . $resp["usersSecondname"];
        $to = $resp['idusers'];
        $from = $user;
        $lastMsg = $conn->query("SELECT `message`,`time` FROM `chat` WHERE (`who_to`='$to' OR `who_to`='$from') AND (`who_from`='$to' OR `who_from`='$from') ORDER BY `chat`.`time` DESC LIMIT 1");
        if ($work = mysqli_fetch_assoc($lastMsg)) {
            $resp["last_msg"] = $work["message"];
            $date = new DateTime($work["time"]);
            $resp["time"] = $date->format('G:i a');
            $date = new DateTime(null);
            $date->format("Y-m-d H:i:s");
            $date = $date->modify("-7 minutes");
            $date = $date->format('Y-m-d H:i:s');
            $query = $conn->query("SELECT `idusers`, `last_online` FROM `users` WHERE `idusers`='$to' AND `last_online`>'$date'");
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
}
