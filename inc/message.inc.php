<?php
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
session_start();
$result = array();
$auth =  new Auth();

if (isset($_SESSION['userId'])) {
	# STAGE 1: GETTING THE USERS
	$result_array = [];
	$result = [];
	if (isset($_POST['from'])) {
		$message = $_POST['message'];
		$from =  $un_ravel->_getUser($_POST['from']);
		$to =  $un_ravel->_getUser($_POST['to']);
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
			$sql = "INSERT INTO `chat` (`who_from`, `who_to`, `message`) VALUES (?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("sss", $from, $to, $message);
			$stmt->execute();
			$stmt->close();
			$result = [
				'code' => 0,
				'msg' => "message sent",
				'type' => 'success'
			];
		} else {
			$result = [
				'code' => 1,
				'msg' => "message empty",
				'type' => 'error'
			];
		}
		print_r(json_encode($result));
	}


	if (isset($_GET['start'])) {
		$start = intval($_GET['start']);
		$from =  $un_ravel->_getUser($_GET['from']);
		$to =  $un_ravel->_getUser($_GET['to']);
		$query = "SELECT * FROM chat WHERE `id`>$start AND ((who_from = '$from' AND who_to = '$to') OR (who_from = '$to' AND who_to = '$from'))  ORDER BY id DESC LIMIT 10";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$row["who_from"] = $un_ravel->_queryUser($row["who_from"], 2);
				$row["who_to"] = $un_ravel->_queryUser($row["who_to"], 2);
				$result_array[] = $row;
			}

			// sort the array by id in ascending order
			usort($result_array, function($a, $b) {
				return $a['id'] - $b['id'];
			});
			print_r(
				json_encode(
					[
						'code' => 1,
						'msg' => 'messages fetched',
						'type' => 'success',
						'data' => $result_array
					]
				)
			);
		} else {
			print_r(
				json_encode(
					[
						'code' => 3,
						'msg' => 'no messages',
						'type' => 'error'
					]
				)
			);
		}
	}
} else {
	print_r(
		json_encode(
			[
				'code' => 2,
				'msg' => 'not logged in',
				'type' => 'error'
			]
		)
	);
}
