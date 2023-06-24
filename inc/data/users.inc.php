<?php

header('content-type: application/json');
require '../dbh.inc.php';
require '../Auth/auth.php';

session_start();
$un_ravel->_isAuth();

if (!$un_ravel->_isAdmin($_SESSION['userId'])) {
	header('HTTP/1.1 403 Forbidden');
}


if (isset($_GET['key'])) {
	$key = $_GET['key'];

	$arr = [];
	$query = "SELECT `idusers`,`uidusers`,`isAdmin`,`date_joined`,`status`,`last_online`,`token` FROM `users`,`auth_key` WHERE `idusers`> 0 AND `auth_key`.`user` = `idusers`";
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


# make user an admin 
if (isset($_POST['admin'])) {
	if ($_SESSION['userId'] != $_POST['admin'] && $_SESSION['userId'] == 1) {
		$id = $_POST['admin'];
		$sql = "UPDATE `users` SET `isAdmin`= '1' WHERE `idusers`=$id";
		$result = $conn->query($sql);
		die(json_encode(['status' => 'success']));
	}
	die(json_encode(['status' => 'error', "message" => "only admin no 1 can do this"]));
}


# disable a user
if (isset($_POST['revoke'])) {
    if ($_SESSION['userId'] != $_POST['revoke'] && $_SESSION['userId'] == 1) {
        $id = $_POST['revoke'];
        $sql = "UPDATE `users` SET `isAdmin`='0' WHERE `idusers`=$id";
        $result = $conn->query($sql);
        die(json_encode(['status' => 'success']));
    } else {
        die(json_encode(['status' => 'error', "message" => "Only admin number 1 can revoke admin status."]));
    }
}


#block user
if (isset($_POST['block'])) {
    $userId = $_SESSION['userId'] ?? null;
    $blockId = $_POST['block'];
    if ($userId !== $blockId && $blockId !== 1) {
        $status = filter_var($_POST['set'], FILTER_VALIDATE_BOOLEAN) ? "active" : "blocked";
        $sql = "UPDATE `users` SET `status`= ? WHERE `idusers`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $blockId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            die(json_encode(['status' => 'success']));
        }
		  die(json_encode(['status' => 'error',"message" => "server error" ]));
    }
    die(json_encode(['status' => 'error', "message" => "Cannot block yourself or main admin account"]));
}
