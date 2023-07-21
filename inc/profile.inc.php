<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'errors/error.inc.php';
header('content-type: application/json');

session_start();


$answer = array();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = isset($_GET['user']) && $_GET['user'] != null ? $_GET['user'] : $id;


if (!is_null($id)) {
  $id = $un_ravel->_getUser($id);
}

if (!is_null($user)) {
  $user = $un_ravel->_getUser($user);
}



if (!empty($id)) {
  $query = "SELECT `users`.`uidusers`, `users`.`usersFirstname`, `users`.`usersSecondname`, `users`.`last_online`, `users`.`profile_picture`, `users`.`followers`, `users`.`following`, `users`.`bio`, `users`.`date_joined`,`chat_auth`,`user_auth` FROM `users`,`auth_key` WHERE `users`.`idusers` = $id AND `auth_key`.`user` ='$id' ";
  $answer['user'] = $conn->query($query)->fetch_assoc();

  $query = "SELECT * FROM `posts` WHERE `userid`='$id' ORDER BY `time` DESC LIMIT 100";
  $result = $conn->query($query);
  $answer['user']['no_posts'] = $result->num_rows;

  $i = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $answer['user']['posts'][$i] = $row;
    $answer['user']['posts'][$i]['user'] = [
      'id' => $answer['user']['user_auth'],
      'name' => $answer['user']['uidusers']
    ];
    // profile picture
    $answer['user']['posts'][$i]['profile_picture'] = $answer['user']['profile_picture'];

    $post_id = $row['id'];
    if ($user != null) {
      $sql = "SELECT * FROM `likes` WHERE `post_id`='$post_id' AND `user_id`='$user'";
      $r = $conn->query($sql)->fetch_assoc();
      $answer['user']['posts'][$i]['liked'] = !is_null($r);
    }

    $sql = "SELECT * FROM `comments` WHERE `post_id`='$post_id'";
    $r = $conn->query($sql);
    $answer['user']['posts'][$i]['comments'] = $r->num_rows;

    $i++;
  }

  print_r(json_encode($answer));
} else {
  $err = new Err(15);
  $err->err($user);
}