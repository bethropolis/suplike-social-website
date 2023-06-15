<?php
require '../dbh.inc.php';
require '../errors/error.inc.php';
require '../Auth/auth.php';
header('content-type: application/json');

$err = new Err();
$err->_set_log('../errors/error.log.txt'); // set where errors will be writen 
if (isset($_GET['key'])) {
	if (empty($_GET['key'])) {
		$err->err('unkown', 7);
		die();
	}

	$key = $_GET['key'];

	$sql = "SELECT 'user' FROM `auth_key` WHERE `token`='$key'";

	$auth = $conn->query($sql);
	if ($auth->fetch_assoc() == null) {
		$err->err('unknown', 7, "not authorised user");
		die();
	}

	$user = mysqli_fetch_assoc($auth);
	if (!isset($_GET['type'])) {
		$err->err($user, 8,"unknown type of data requested");
		die();
	}

	$r = [];
	$arr = [];
	$arru = []; //users
	$arruo = []; //users online
	$arrp = []; // post
	$arrc = []; // chat
	$arrl = []; // likes
	$arrf = []; // follows
	$arrd = []; // comment
	$arrs = []; // share
	$type = $_GET['type'];
	$date = new DateTime('now', $timeZone);
	$date->format("Y-m-d H:i:s");
	$date = $date->modify("-7 days");
	$date = $date->format('Y-m-d');
	$print;

	function _get_user($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `idusers`,`date_joined`,`last_online`,`uidusers` FROM `users` WHERE `date_joined`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['date_joined'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arru'] = $arr;
		}

		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}

	function _get_user_online($stat = false)
	{
		global $conn, $timeZone;
		$arr = [];
		$date = new DateTime("now", $timeZone);
		$date->format("Y-m-d H:i:s");
		$arr['today'] = $date->format('l');
		$date = $date->modify("-7 days");
		$date = $date->format('Y-m-d');
		$sql = "SELECT `idusers`,`last_online` FROM `users` WHERE `last_online`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['last_online'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arruo'] = $arr;
		}

		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}


	function _get_comment($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `id`,`user`,`post_id`,`date` FROM `comments` WHERE `date`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['date'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arrd'] = $arr;
		}
		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}


	function _get_post($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `id`,`time` FROM `posts` WHERE `time`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['time'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arrp'] = $arr;
		}
		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}

	function _get_chat($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `id`,`time` FROM `chat` WHERE `time`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['time'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arrc'] = $arr;
		}
		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}

	function _get_share($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `id`,`user`,`time` FROM `share` WHERE `time`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['time'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arrs'] = $arr;
		}
		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}

	function _get_like($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `id`,`time` FROM `likes` WHERE `time`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['time'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arrl'] = $arr;
		}

		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}

	function _get_follow($stat = false)
	{
		global $date, $conn, $timeZone;
		$arr = [];
		$sql = "SELECT `id`,`time` FROM `following` WHERE `time`>'$date'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dt = new DateTime($row['time'], $timeZone);
			$dt = $dt->format('l');
			$arr[$dt][] = $row;
			$GLOBALS['arrf'] = $arr;
		}
		if ($stat) {
			print_r(json_encode($arr));
			die();
		}
	}

	switch ($type) {
		case 'user':
			_get_user(true);
			break;
		case 'userOnline':
			_get_user_online(true);
			break;
		case 'post':
			_get_post(true);
			break;
		case 'chat':
			_get_chat(true);
			break;
		case 'like':
			_get_like(true);
			break;
		case 'follow':
			_get_follow(true);
			break;
		case 'comment':
			_get_comment(true);
			break;
		case 'share':
			_get_share(true);
			break;
		case 'all':
			_get_user();
			_get_user_online();
			_get_post();
			_get_chat();
			_get_share();
			_get_comment();
			_get_like();
			_get_follow();
			break;
		default:
			$err->err($user, 14);
			die();
			break;
	}

	$arr['users'] = $arru;
	$arr['users_online'] = $arruo;
	$arr['posts'] = $arrp;
	$arr['chat'] =  $arrc;
	$arr['like'] =  $arrl;
	$arr['comment'] = $arrd;
	$arr['follow'] = $arrf;
	$arr['share'] = $arrs;

	print_r(json_encode($arr));
} else {
	$err->err('unknown', 7);
}
