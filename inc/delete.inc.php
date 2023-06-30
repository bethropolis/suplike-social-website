<?php
header('content-type: application/json');
require_once 'dbh.inc.php';
require_once 'Auth/auth.php';
require_once '../api/v1/bot/bot.php';
require_once 'errors/error.inc.php';
session_start();
$un_ravel->_isAuth();

$isAdmin = $un_ravel->_isAdmin($_SESSION['userId']);
if (isset($_POST['delete_profile'])) {

	$post_user = $_POST['user'];
	$isBot = $un_ravel->_isBot($post_user);


	if ($isAdmin) {
		$user = $_POST['user'];
	} elseif ($isBot) {
		if ($bot->botBelongsToUser($post_user, $_SESSION['userId'])) {
			$user = $post_user;
		} else {
			die(json_encode(["status" => "error"]));
		}
	} else {
		$user = $_SESSION['userId'];
	}

	# die(print_r(["isbot" => $isBot, "isAdmin" => $isAdmin, "user" => $user]));
	if ($user == 1) {
		die(json_encode(["status" => "error"]));
	}
	$query = "SELECT * FROM `users` WHERE `idusers`='" . $user . "'";
	$result = $conn->query($query)->fetch_assoc();
	if (!($isAdmin || $isBot)) {
		$pwdCheck = password_verify($post_user, $result['pwdUsers']);
		if ($pwdCheck === false) {
			header('Location: ../settings.php?delete&err=wrongpassword');
			exit();
		}
	}



	$name = $result['uidusers'];

	# delete users posts
	$sql = "DELETE FROM `posts` WHERE `userid`=" . $user;
	$conn->query($sql);

	# delete likes
	$sql = "DELETE FROM `likes` WHERE `user_id`=" . $user;
	$conn->query($sql);

	# delete followers and follows
	$sql = "DELETE FROM `following` WHERE `following`.`user`=" . $user . " OR `following`.`following`=" . $user;
	$conn->query($sql);
	# delete messages 
	$sql = "DELETE FROM `chat` WHERE `chat`.`who_from` =$user OR `chat`.`who_to` = $user;";
	$conn->query($sql);
	# delete auth keys 
	$sql = "DELETE FROM `auth_key` WHERE `auth_key`.`user`=" . $user;
	$conn->query($sql);

	# delete bot
	$sql = "DELETE FROM `bots` WHERE `userid`=" . $_SESSION['userId'] . " AND `bot_id`=" . $user;
	$conn->query($sql);


	# delete user
	$sql = "DELETE FROM `users` WHERE `idusers`=" . $user;
	$conn->query($sql);

	# delete stories
	$sql = "DELETE FROM `stories` WHERE `userid`=" . $user;
	$conn->query($sql);

	# update comments to deleted
	$sql = "UPDATE `comments` SET `user`='deleted' WHERE `user`='$name'";
	$conn->query($sql);

	# delete notifications
	$sql = "DELETE FROM `notify` WHERE `user`=" . $user;
	$conn->query($sql);
	# delete api
	$sql = "DELETE FROM `api` WHERE `api`.`user`=" . $user;
	$conn->query($sql);

	# delete sessions
	$sql = "DELETE FROM `session` WHERE `user_id`=" . $user;
	$conn->query($sql);

	if (!$isBot) {
		# delete  users bot
		$sql = "DELETE FROM `bots` WHERE `userid`=" . $_SESSION['userId'];
		$conn->query($sql);
	}


	if (!($isAdmin || $isBot)) {
		header('Location: logout.inc.php?acc_deleted');
	}
	die(json_encode(["status" => "success"]));
} else {
	$err = new Err(15);
	$err->err('Wrong Session', null, 'account could not be deleted');
	die();
}
