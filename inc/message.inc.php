<?php
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$result = [];
$auth =  new Auth();

if (isset($_POST['from'])) {
	$message = $_POST['message'];
	$from =  $auth->_getUser($_POST['from']);
	$to =  $auth->_getUser($_POST['to']);

	if ($from === $to) {
		die(json_encode(
			[
				'code' => 6,
				'msg' => "cannot message yourself",
				'type' => 'error'
			]
		));
	}

	if (!empty($message) && !empty($from)) {
		$query = "INSERT INTO chat (`message`, `who_from`, `who_to` ) VALUES ('$message','$from', '$to')";
		$conn->query($query);
		print_r(
			json_encode(
				[
					'code' => 21,
					'msg' => 'message sent',
					'type' => 'success'
				]
			)
		);
	} else {
		print_r(
			json_encode(
				[
					'code' => 2,
					'msg' => 'message not sent',
					'type' => 'error'
				]
			)
		);
	}
}


if (isset($_GET['start'])) {
	$start = intval($_GET['start']);
	$from =  $auth->_getUser($_GET['from']);
	$to =  $auth->_getUser($_GET['to']);
	$items = $conn->query("SELECT * FROM `chat` WHERE `id`>" . $start . " AND (`who_to`='$to' OR `who_to`='$from')  AND (`who_from`='$from' OR `who_from`='$to') ORDER BY `chat`.`time` LIMIT 15;");
	while ($row = $items->fetch_assoc()) {
		$row["who_from"] = $auth->_queryUser($row["who_from"], 2);
		$row["who_to"] = $auth->_queryUser($row["who_to"], 2);
		$result['items'][] = $row;
	}
	print_r(json_encode($result));
}
