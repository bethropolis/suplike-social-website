<?php
require_once  'dbh.inc.php';
require_once  'Auth/auth.php';
require_once  'errors/error.inc.php';
require_once  'extra/notification.class.php';
require_once   __DIR__.'/../api/v1/bot/bot.php';
header('content-type: application/json');
$notification = new Notification();
session_start();
# this was a 100% copy from like.inc.php,
# I will consider merging them in future

$un_ravel->_isAuth();

if (isset($_GET['user'])) {

    if (empty($_GET['user']) || empty($_GET['key'])) {
        $error->err('Empty',33,'Empty variables provided');
        die();
    }

    $following = $un_ravel->_getUser($_SESSION['token']);
    $followed = $un_ravel->_getUser($_GET['following']);
    $key = $_GET['key'];

    if (!($key == 'false' || $key == 'true')) {
        $error->err('Invalid',33,'incorrect key value');
        die();
    }

    if ($following == $followed) {
        print_r(
            json_encode(
                array(
                    'type' => 'error',
                    'code' => 35,
                    'message' => 'cannot follow yourself'
                )
            )

        );

        die();
    }

    if (!is_numeric($followed) || !is_numeric($following)) {
        $error->err('Invalid',33,'invalid value in parameter');
        die();
    }

    $sql = "SELECT * FROM `users` WHERE `idusers`=$following";
    $result = $conn->query($sql)->fetch_assoc();
    if (is_null($result)) {
        $err = new Err(1);
        $err->err($_GET['user']);
        die();
    } else {
        $following_user_follows = $result['following'];
    }


    $sql = "SELECT * FROM `users` WHERE `idusers`='$followed'";
    $result = $conn->query($sql)->fetch_assoc();
    if (is_null($result)) {
        $err = new Err(1);
        $err->err('Null');
        die();
    } else {
        $followed_user_followers = $result['followers'];
    }



    $sql = "SELECT * FROM `following` WHERE `user`='$following' AND `following`='$followed'";
    $result = $conn->query($sql)->fetch_assoc();



    if (is_null($result) && $key == 'true') {
        $following_user_follows = $following_user_follows + 1;
        $followed_user_followers = $followed_user_followers + 1;
        $sql  = "INSERT INTO following (`user`,`following`) VALUES ($following, $followed)";
        $conn->query($sql);
        $id = $conn->insert_id;
        $user = $un_ravel->_username($following);
        $notification->notify($followed,"$user followed you", 'follow');

        if($un_ravel->_isBot($followed)){
            print_r([$followed,$id,$_GET['following']]);
			$bot->setBot($followed);
			$bot->send("follow", $_GET['following'], $id);
		}
    }

    if (!is_null($result) && $key == 'false') {
        $following_user_follows = $following_user_follows - 1;
        $followed_user_followers = $followed_user_followers - 1;
        $sql  = "DELETE FROM `following` WHERE `user` = '$following' AND `following`='$followed'";
        $conn->query($sql);
    }

    if (!is_null($result) && $key == 'true') {
        $err = new Err();
        $err->err('Followed',12, "already followed the user");
        die();
    }

    if (is_null($result) && $key == 'false') {
        $err = new Err();
        $err->err('Error');
        die();
    }
    $sql = "UPDATE `users` SET `followers` = ' $followed_user_followers' WHERE `idusers` = '$followed';";
    mysqli_query($conn, $sql);

    $sql = "UPDATE `users` SET `following` = '$following_user_follows' WHERE `idusers` = '$following';";
    mysqli_query($conn, $sql);
    # notify user that he has been followed

    # actually I don't think we will be merging them again; 
    print_r(
        json_encode(
            array(
                'type' => 'success',
                'code' => 21,
                'post' => 'successful'

            )
        )
    );
}
