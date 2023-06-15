<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'errors/error.inc.php';
header('content-type: application/json');
# this is an api that gives the whole of user data on request
# from the database.
# the api is called by the frontend
# the api is called by the frontend
session_start();

//auth check
$un_ravel->_isAuth();


$answer = array();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = isset($_GET['user']) ? $_GET['user'] : null;


if (!is_null($id)) {
  $id =  $un_ravel->_getUser($id);
}

if (!is_null($user)) {
  $user = $un_ravel->_getUser($user);
}



if (!empty($id)) {
  $query = "SELECT `users`.`uidusers`, `users`.`usersFirstname`, `users`.`usersSecondname`, `users`.`last_online`, `users`.`profile_picture`, `users`.`followers`, `users`.`following`, `users`.`bio`, `users`.`date_joined`,`chat_auth`,`user_auth` FROM `users`,`auth_key` WHERE `users`.`idusers` = $id AND `auth_key`.`user` ='$id' ";
  $answer['user'] = $conn->query($query)->fetch_assoc();
  $query = "SELECT * FROM `posts` WHERE `userid`='$id'";
  $result = $conn->query($query);
  $answer['user']['no_posts'] = $result->num_rows;
  $i = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $answer['posts'][$i] = $row;
    $answer['posts'][$i]['user'] = [
      'id' => $answer['user']['user_auth'],
      'name' => $answer['user']['uidusers']
    ];
    // profile picture
    $answer['posts'][$i]['profile_picture'] = $answer['user']['profile_picture'];
    $post_id = $row['id'];
    if ($user != null) {
      $sql = "SELECT * FROM `likes` WHERE `post_id`='$post_id' AND `user_id`='$user'";
      $r = $conn->query($sql)->fetch_assoc();
      if (!is_null($r)) {
        $answer['posts'][$i]['liked'] = true;
      } else {
        $answer['posts'][$i]['liked'] = false;
      }
    }
    $sql = "SELECT * FROM `comments` WHERE `post_id`='$post_id'";
    $r = $conn->query($sql);
    $answer['posts'][$i]['comments'] = $r->num_rows;
    $i++;
  }
  print_r(json_encode($answer));
} else {
  $err = new Err(15);
  $err->err($user);
}
