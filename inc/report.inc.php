<?php
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';

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

if (isset($_POST['del'])) {
	$id = $_POST['del'];
	$sql = "UPDATE `reports` SET `delt`=TRUE WHERE `post_id`=$id";
	$conn->query($sql);

	$sql = "DELETE FROM `posts` WHERE `id`=$id";
	$conn->query($sql);
	print_r(json_encode("deleted"));
	exit();
}

if (isset($_GET['report'])) {
	$type = $_GET['type'];
	$arr = [];
	$sql = "SELECT * FROM `reports` WHERE `delt`=$type"; 
	$rsp = $conn->query($sql);
	while ($row = $rsp->fetch_assoc()) {
		$arr[] = $row;
	}
	print_r(json_encode($arr));
}

if (isset($_GET['comment'])) {
	$c = $_GET['comment'];
	$sql = "INSERT INTO `reports` (`comment_id`,`is_comment`) VALUE ('$c',TRUE)";
	$conn->query($sql);
	$from = $_SERVER['HTTP_REFERER'] . '&act=reported';
	header('Location: ' . $from);
}

if (isset($_POST['delc'])) {  
	$c = $_POST['delc']; 
	$sql = "UPDATE `reports` SET `delt`=TRUE WHERE `comment_id`=$id";
	$conn->query($sql);
	$sql = "DELETE FROM `comments` WHERE `comments`.`id` = ".$c; 
	$conn->query($sql);
	print_r(json_encode("deleted"));
}
