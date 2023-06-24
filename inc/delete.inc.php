<?php
header('content-type: application/json');
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'errors/error.inc.php';
session_start();
$un_ravel->_isAuth();

$isAdmin = $un_ravel->_isAdmin($_SESSION['userId']);
if (isset($_POST['delete_profile'])) {



	$old = $_POST['user'];
	if ($isAdmin) {
		$user = $_POST['user'];
	} else {
		$user = $_SESSION['userId'];
	}
	if ($user == 1) {
		die(json_encode(["status" => "error"]));
	}
	$query = "SELECT * FROM `users` WHERE `idusers`='" . $user . "'";
	$result = $conn->query($query)->fetch_assoc();
	if (!$isAdmin) {
		$pwdCheck = password_verify($old, $result['pwdUsers']);
		if ($pwdCheck === false) {
			header('Location: ../settings.php?delete&err=wrongpassword');
			exit();
		}
	}
	$name = $_SESSION['userUid'];

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

	# delete user
	$sql = "DELETE FROM `users` WHERE `idusers`=" . $user;
	$conn->query($sql);

	# delete stories
	$sql = "DELETE FROM `stories` WHERE `userid`=" . $user;
	$conn->query($sql);

	# update comments to deleted
	$sql = "UPDATE `comments` SET `comment`='[deleted]', `user`='deleted' WHERE `user`='$name'";
	$conn->query($sql);

	# delete notifications
	$sql = "DELETE FROM `notify` WHERE `user`=" . $user;
	$conn->query($sql);
	# delete api
	$sql = "DELETE FROM `api` WHERE `api`.`user`=" . $user;
	$conn->query($sql);

	if (!$isAdmin) {
		header('Location: logout.inc.php?acc_deleted');
	}
	die(json_encode(["status" => "success"]));
} else {
	$err = new Err(15);
	$err->err('Wrong Session', null, 'account could not be deleted');
	die();
}
