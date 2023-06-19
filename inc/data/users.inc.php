<?php

header('content-type: application/json');
require '../dbh.inc.php';
require '../Auth/auth.php';

session_start();
$un_ravel->_isAuth();

if(!$un_ravel->_isAdmin($_SESSION['userId'])){
	header('HTTP/1.1 403 Forbidden');
}


if (isset($_GET['key'])) {
	$key = $_GET['key'];

	$arr = [];
	$query = "SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`date_joined`,`last_online` FROM `users` WHERE `idusers`> 0";
	$result = $conn->query($query);
	$i = 0;
	while ($row = mysqli_fetch_assoc($result)) {

		$arr[$i] = $row;
		$i++;
	}
	print_r(json_encode($arr));

}

if (isset($_GET['online'])) {
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
	}
	print_r(json_encode($arr));
  }
  