<?php
require '../dbh.inc.php';
require '../Auth/auth.php';
header("Content-Type: application/json");
session_start();
$un_ravel->_isAuth();

if (!isset($_SESSION['userId'])) {
    die(json_encode([
        'code' => 4,
        'msg' => "You are not logged in",
        'type' => 'error'
    ]));
}

if (isset($_POST["generate"])) {
    // cannot change token if an hour has not passed
    $id = $_SESSION['userId'];

    $current_time = time();
    $one_hour_ago = strtotime('-1 hour', $current_time);
    $sql = "SELECT `date` FROM `api` WHERE `user` = $id ORDER BY `date` DESC LIMIT 1";

    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $last_token_time = strtotime(mysqli_fetch_assoc($result)['date']);
        if ($last_token_time > $one_hour_ago) {
            die(json_encode([
                'code' => 4,
                'msg' => "You can only change your token once every hour.",
                'type' => 'error'
            ]));
        }
    }

    $token = bin2hex(random_bytes(32));
    try {
        // check if token already exists else update
        $sql = "SELECT * FROM `api` WHERE `user` = $id";
        if (mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
            $sql = "UPDATE `api` SET `key` = '$token', `date` = NOW() WHERE `user` = $id";
        } else {
            $sql = "INSERT INTO `api` (`user`, `key`, `date`) VALUES ($id, '$token', NOW())";
        }
        mysqli_query($conn, $sql);
    } catch (Exception $e) {
        die(json_encode([
            'code' => 4,
            'msg' => "Something went wrong",
            'type' => 'error'
        ]));
    }

    die(json_encode([
        'code' => 1,
        'msg' => "Success",
        'token' => $token
    ]));
} elseif (isset($_POST["delete"])) {
    // Delete token if at least 30 minutes have passed
    $id = $_SESSION['userId'];

    $current_time = time();
    $thirty_minutes_ago = strtotime('-10 minutes', $current_time);
    $sql = "SELECT `date` FROM `api` WHERE `user` = $id ORDER BY `date` DESC LIMIT 1";

    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $last_token_time = strtotime(mysqli_fetch_assoc($result)['date']);
        if ($last_token_time <= $thirty_minutes_ago) {
            $delete_sql = "DELETE FROM `api` WHERE `user` = $id";
            mysqli_query($conn, $delete_sql);

            die(json_encode([
                'code' => 1,
                'msg' => "Token deleted",
            ]));
        } else {
            die(json_encode([
                'code' => 4,
                'msg' => "You cannot delete the token within 30 minutes of its generation.",
                'type' => 'error'
            ]));
        }
    } else {
        die(json_encode([
            'code' => 4,
            'msg' => "Token does not exist",
            'type' => 'error'
        ]));
    }
} else {
    die(json_encode([
        'code' => 4,
        'msg' => "Invalid request",
        'type' => 'error'
    ]));
}