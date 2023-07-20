<?php
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';
include_once '../plugins/load.php';

use Bethropolis\PluginSystem\System;

session_start();
// Auth check
$un_ravel->_isAuth();

header('Content-Type: application/json');

if (isset($_POST['id'])) {
	if (!empty($_POST['id'])) {
		$id = $_POST['id'];
		$sql = "INSERT INTO `reports` (`post_id`) VALUES ($id)";
		$result = $conn->query($sql);
		System::executeHook('report_hook', null, ['id' => $id, "type" => "post"]);
		echo json_encode("reported");
		exit();
	} else {
		echo json_encode("error could not be reported");
	}
}

if (isset($_GET['comment'])) {
	$c = $_GET['comment'];
	$sql = "INSERT INTO `reports` (`comment_id`,`is_comment`) VALUE ('$c',TRUE)";
	$conn->query($sql);
	System::executeHook('report_hook', null, ['id' => $id, "type" => "comment"]);
	$from = $_SERVER['HTTP_REFERER'] . '&act=reported';
	header('Location: ' . $from);
	exit();
}

// ADMIN SECTION

if (!$un_ravel->_isAdmin($_SESSION['userId'])) {
	header('HTTP/1.1 401 Unauthorized');
	exit();
}

if (isset($_POST['del'])) {
	$id = $_POST['del'];
	$sql = "UPDATE `reports` SET `delt`=TRUE WHERE `post_id`=$id";
	$conn->query($sql);

	$sql = "DELETE FROM `posts` WHERE `id`=$id";
	$conn->query($sql);
	echo json_encode("deleted");
	exit();
}

if (isset($_POST['delc'])) {
	$id = $_POST['id'];
	$c = $_POST['delc'];
	$sql = "UPDATE `reports` SET `delt`=TRUE WHERE `comment_id`=$id";
	$conn->query($sql);
	$sql = "DELETE FROM `comments` WHERE `comments`.`id` = " . $c;
	$conn->query($sql);
	echo json_encode("deleted");
	exit();
}

if (isset($_GET['report'])) {
	$type = $_GET['type'];
	$arr = [];
	$sql = "SELECT * FROM `reports` WHERE `delt`=?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $type);
	$stmt->execute();
	$response = $stmt->get_result();
	while ($row = $response->fetch_assoc()) {
		$post_id = "";
		if ($row['is_comment']) {
			$sql = "SELECT `post_id` FROM `comments` WHERE `id` = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $row['comment_id']);
		} else {
			$sql = "SELECT `post_id` FROM `posts` WHERE `id` = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $row['post_id']);
		}
		$stmt->execute();
		$rsp = $stmt->get_result();
		if ($rsp->num_rows > 0) {
			$post_id = $rsp->fetch_assoc()['post_id'];
			$row['slug'] = $post_id;
			$arr[] = $row;
		}
	}

	echo json_encode($arr);
}
