<?php

// Create database connection to database
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/xss-clean.func.php';
header('content-type: application/json');
// Initialize message variable 
// If upload button is clicked ...
    session_start();


if (isset($_POST['upload'])) {

    $type = $_POST['type'];
    $user = $_SESSION['userId'];
    $d = new DateTime("now", $timeZone);
    $image_text = mysqli_real_escape_string($conn, $_POST['posttext']);
    $image_text = xss_clean($image_text);
    $image_text = htmlspecialchars($image_text);
    $image_text = trim($image_text);
    $image_text  = preg_replace('~[\r\n]+~', '', $image_text);



    if ($_POST['type'] == 'img') {
        // mentioning all my variables that I will use 
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $dot = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($dot));
        $extensions = array("jpeg", "jpg", "png", "gif", "webp"); //the allowed extensions
        $image = rand(12, 2000) . "." . $file_ext;
        if (in_array($file_ext, $extensions)) {
            if ($file_size < 6291456) {
                $target = "../img/" . $image;
                $sql = "INSERT INTO posts (`post_id`,`image`, `image_text`, `type`, `userid`, `date_posted`, `day`) VALUES (?,?, ?, ?, ?, ?,?)";
                move_uploaded_file($file_tmp, $target);
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", bin2hex(openssl_random_pseudo_bytes(4)), $image, $image_text, $type, $user, $d->format('j M'), $d->format('l'));
                $stmt->execute();
                header("Location: ../index.php?post=success");
                die();
            } else {
                header('Location: ../index.php?upload=filetobig');
            }
        } else {
            header('Location: ../index.php?upload=extnotallowed');
        }
    } else if ($_POST['type'] == 'txt') {
        if ($_POST['upload'] == 'post') {
            if ($image_text === "") {
                header("Location: ../post.php?error=emptystr");
                die();
            }
        }
        // variables  
        $sql = "INSERT INTO posts (`post_id`,`image_text`, `userid`,`type` ,`date_posted`, `day`) VALUES (?,?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $date = $d->format('j M');
        $day = $d->format('l');
        $stmt->bind_param("ssssss", bin2hex(openssl_random_pseudo_bytes(4)), $image_text, $user, $type, $date, $day);
        $stmt->execute();
        if ($_POST['upload'] == 'post') {
            header("Location: ../post.php?upload=success");
        } else {
            header("Location: ../index.php?upload=success");
        }
        die();
    }
}

#-------------------GET POSTs------------------#


if (isset($_GET['user'])) {

    # STAGE 1: GETTING THE USERS
    $result_array = [];
    $user = $un_ravel->_getUser($_GET['user']);
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
                # replace \n and \r with space
               $text = $result_array[$i]['image_text'];
                $text = trim(preg_replace('/\s+/', ' ', $text));
                $text = trim(preg_replace('/\s\s+/', ' ', $text));
                $result_array[$i]['profile_picture'] = $un_ravel->_profile_picture($key["idusers"]);
                $result_array[$i]['image_text'] = $text;
                $result_array[$i]['user'] = ['id' => $un_ravel->_queryUser($acc, 4), 'name' => $usr];
                $id = $row['id'];
                $post_id = $row['post_id'];
                $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";
                $r = $conn->query($sql)->fetch_assoc();

                # STAGE 3: DETERMINING IF THE USER HAS LIKED IT    
                if (!is_null($r)) {
                    $result_array[$i]['liked'] = true;
                } else {
                    $result_array[$i]['liked'] = false;
                }
                # get number of comments for post
                $sql = "SELECT * FROM `comments` WHERE `post_id`='$post_id'";
                $s = $conn->query($sql);
                $result_array[$i]['comments'] = $s->num_rows;
                $i++;
            }
        }
    }
    # STAGE 4: SELECTING THE USERS OWN POST
    $sql = "SELECT * FROM `posts` WHERE `userid`='$user' ORDER BY `posts`.`id` DESC ";
    $ans = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($ans)) {
        $result_array[$i] = $row;
        $text = $result_array[$i]['image_text'];
        $text = preg_replace('~[\r\n]+~', ' ', $text);
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $text = trim(preg_replace('/\s\s+/', ' ', $text));
        $text = str_replace(array("\r\n","\r"),"",$text);
        $result_array[$i]['profile_picture'] = $un_ravel->_profile_picture($user);
        $result_array[$i]['image_text'] = $text;
        $result_array[$i]['user'] = true;
        $id = $row['id'];
        $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";
        $r = $conn->query($sql)->fetch_assoc();
        if (!is_null($r)) {
            $result_array[$i]['liked'] = true;
        } else {
            $result_array[$i]['liked'] = false;
        }
        # get comments count
        $id = $row['post_id'];
        $sql = "SELECT * FROM `comments` WHERE `post_id`='$id'";
        $r = $conn->query($sql);
        $result_array[$i]['comments'] = $r->num_rows;
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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM `posts` WHERE `post_id`='$id'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $user = $row['userid'];
    # get user
    $sql = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers`=$user AND `auth_key`.`user` = $user ";
    $resp = $conn->query($sql)->fetch_assoc();
    $row['user'] = ['id' => $un_ravel->_queryUser($user, 4), 'name' => $resp['uidusers'], 'profile_picture' => $resp['profile_picture']];
    $post_id = $row['post_id'];
    # gen num of
    $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";
    $r = $conn->query($sql)->fetch_assoc();
    if (!is_null($r)) {
        $row['liked'] = true;
    } else {
        $row['liked'] = false;
    }
    $sql = "SELECT * FROM `comments` WHERE `post_id`='$post_id'";
    $r = $conn->query($sql);
    $row['comments'] = $r->num_rows;
    print_r(json_encode($row));
}

if(isset($_GET['del_post'])){
    // first check if the user is the owner of the post
    $id = $_GET['del_post'];
    $sql = "SELECT * FROM `posts` WHERE `post_id`='$id'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $user = $row['userid'];
    if($user == $_SESSION['userId']){
        $sql = "DELETE FROM `posts` WHERE `post_id`='$id'";
        $conn->query($sql);
        // delete comments
        $sql = "DELETE FROM `comments` WHERE `post_id`='$id'";
        $conn->query($sql);
        // delete likes
        $sql = "DELETE FROM `likes` WHERE `post_id`='$id'";
        $conn->query($sql);
        header("Location: ../");
    }else{
        // status code 403
        header("HTTP/1.0 403 Forbidden");
    }
}