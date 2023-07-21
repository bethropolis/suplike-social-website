<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
header('content-type: application/json');
session_start();

$un_ravel->_isAuth();



if (isset($_GET['user'])) {
    $user = $un_ravel->_getUser($_GET['user']);
    $stmt = $conn->prepare("SELECT * FROM `following` WHERE `user`=?");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $answer = [];
    $i = 0;

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $f = $row['following'];

            $stmt = $conn->prepare("SELECT `idusers`,`uidusers`,`usersFirstname`,`usersSecondname`,`last_online`,`profile_picture`,`token`,`chat_auth` FROM `users`,`auth_key` WHERE `users`.`idusers` = ? AND `auth_key`.`user` = ?");
            $stmt->bind_param("ii", $f, $f);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!is_null($row)) {
                $answer['users'][$i] = $row;
                $user_id = $row['idusers'];
                $last_online = $row['last_online'];
                $answer['users'][$i]['full_name'] = trim($row['usersFirstname'] . " " . $row['usersSecondname']);
                if ($answer['users'][$i]['full_name'] == "") {
                    $answer['users'][$i]['full_name'] = $row['uidusers'];
                }

                $sql = "SELECT `message`,`time`,`type` FROM `chat` WHERE (`who_to`='$user_id' OR `who_to`='$user') AND (`who_from`='$user_id' OR `who_from`='$user') ORDER BY `chat`.`time` DESC LIMIT 1";
                $r = $conn->query($sql)->fetch_assoc();

                if (!is_null($r)) {
                    $time = $r['time'];
                    $type = $r['type'];
                    $message = $r['message'];
                    date_default_timezone_set('UTC');
                    $date = new DateTime($time);
                    $answer['users'][$i]['day'] = $date->format('l');
                    $answer['users'][$i]['last_msg'] = $message;
                    $answer['users'][$i]['time'] = $date->format('g:i a');
                    $answer['users'][$i]['actual_time'] = date('c', strtotime($time));
                    $answer['users'][$i]['type'] = $type;
                } else {
                    $answer['users'][$i]['last_msg'] = "";
                    $answer['users'][$i]['type'] = "";
                    $answer['users'][$i]['actual_time'] = null;
                    $answer['users'][$i]['day'] = null;
                }

                date_default_timezone_set('UTC');
                $now_minus_7_minutes = new DateTime('now');
                date_sub($now_minus_7_minutes, date_interval_create_from_date_string('7 minutes'));
                $last_online_date = new DateTime($last_online);
                if ($last_online_date > $now_minus_7_minutes) {
                    $answer['users'][$i]['online'] = true;
                } else {
                    $answer['users'][$i]['online'] = false;
                }

                $i++;
            } else {

                continue;
            }
        }

        usort($answer['users'], function ($a, $b) {
            if (isset($a['last_online']) && isset($b['last_online'])) {
                return strtotime($b['last_online']) - strtotime($a['last_online']);
            } elseif (isset($a['last_online'])) {
                return -1;
            } elseif (isset($b['last_online'])) {
                return 1;
            } else {
                return 0;
            }
        });

        usort($answer['users'], function ($a, $b) {
            if (isset($a['actual_time']) && isset($b['actual_time'])) {
                return strtotime($b['actual_time']) - strtotime($a['actual_time']);
            } elseif (isset($a['actual_time'])) {
                return -1;
            } elseif (isset($b['actual_time'])) {
                return 1;
            } else {
                return 0;
            }
        });
        print_r(json_encode($answer));
    } else {
        print_r(json_encode(array('users' => [])));
    }
}
