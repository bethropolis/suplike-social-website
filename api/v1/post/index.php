<?php
require '../r.php';

if (isset($_GET['user_key'])) {
    # STAGE 1: GETTING THE USERS
    $result_array = [];
    $user = $un_ravel->_getUser($_GET['user_key']);
    $arr = [];
    $query = "SELECT * FROM `following` WHERE `user`=$user";
    $result = $conn->query($query);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $f = $row['following'];
        $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers`=$f AND `auth_key`.`user` = $f ";
        $resp = $conn->query($sql)->fetch_assoc();
        $arr[$i] = $resp;
        $i++;
    }

    # STAGE 2:  GETTING THE POST FROM EACH USER 
    $i = 0;
    foreach ($arr as $key) {
        $acc = $key["idusers"];
        $usr = $key["uidusers"];
        $sql = "SELECT * FROM `posts` WHERE `userid`='$acc' ORDER BY `posts`.`id` DESC";
        $ans = mysqli_query($conn, $sql);
        if ($ans) {
            while ($row = mysqli_fetch_assoc($ans)) {
                $result_array[$i] = $row;
                $result_array[$i]['user'] = ['id' => $un_ravel->_queryUser($acc, 4), 'name' => $usr];
                $id = $row['id'];
                $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";
                $r = $conn->query($sql)->fetch_assoc();

                # STAGE 3: DETERMINING IF THE USER HAS LIKED IT    
                if (!is_null($r)) {
                    $result_array[$i]['liked'] = true;
                } else {
                    $result_array[$i]['liked'] = false;
                }

                $i++;
            }
        }
    }
    # STAGE 4: SELECTING THE USERS OWN POST
    $sql = "SELECT * FROM `posts` WHERE `userid`='$user' ORDER BY `posts`.`id` DESC ";
    $ans = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($ans)) {
        $result_array[$i] = $row;
        $result_array[$i]['user'] = true;
        $id = $row['id'];
        $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";
        $r = $conn->query($sql)->fetch_assoc();
        if (!is_null($r)) {
            $result_array[$i]['liked'] = true;
        } else {
            $result_array[$i]['liked'] = false;
        }
        $i++;
    }
    if ($result_array == null) {
        print_r(json_encode(null));
        die();
    }

    function invenDescSort($item1, $item2)
    {
        if ($item1['time'] == $item2['time']) return 0;
        return ($item1['time'] < $item2['time']) ? 1 : -1;
    }
    usort($result_array, 'invenDescSort');
    print_r(json_encode($result_array));
}
