<?php
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';

session_start();
//auth check
$un_ravel->_isAuth();

header('Content-Type: application/json');
if (isset($_POST['id'])) {
	if (!empty($_POST['id'])) {
		$id = $_POST['id'];
		$sql = "INSERT INTO `reports` (`post_id`) VALUES ($id)";
		$result = $conn->query($sql);
		print_r(json_encode("reported"));
		exit();
	} else {
		print_r(json_encode("error could not be reported"));
	}
}

if (isset($_GET['comment'])) {
	$c = $_GET['comment'];
	$sql = "INSERT INTO `reports` (`comment_id`,`is_comment`) VALUE ('$c',TRUE)";
	$conn->query($sql);
	$from = $_SERVER['HTTP_REFERER'] . '&act=reported';
	header('Location: ' . $from);
}



// ADMIN SECTION

if (!$un_ravel->_isAdmin($_SESSION['userId'])) {
	print_r(json_encode("error not auth"));
	exit();
}

if (isset($_POST['del'])) {
	$id = $_POST['del'];
	$sql = "UPDATE `reports` SET `delt`=TRUE WHERE `post_id`=$id";
	$conn->query($sql);

	$sql = "DELETE FROM `posts` WHERE `id`=$id";
	$conn->query($sql);
	print_r(json_encode("deleted"));
	exit();
}

if (isset($_POST['delc'])) {
	$id = $_POST['id'];
	$c = $_POST['delc'];
	$sql = "UPDATE `reports` SET `delt`=TRUE WHERE `comment_id`=$id";
	$conn->query($sql);
	$sql = "DELETE FROM `comments` WHERE `comments`.`id` = " . $c;
	$conn->query($sql);
	print_r(json_encode("deleted"));
}

if (isset($_GET['report'])) {
	$type = $_GET['type'];
	$arr = [];
	$sql = "SELECT * FROM `reports` WHERE `delt`=$type";
	$response = $conn->query($sql);
	while ($row = $response->fetch_assoc()) {
		if ($row['is_comment']) {
			$sql = "SELECT `post_id` FROM `comments` WHERE `id` = " . $row['comment_id'];
		} else {
			$sql = "SELECT `post_id` FROM `posts` WHERE `id` = " . $row['post_id'];
		}
		$rsp = $conn->query($sql);
		$post_id = $rsp->fetch_assoc()['post_id'];
		$row['slug'] = $post_id;

		$arr[] = $row;
	}
	
	print_r(json_encode($arr));
}
