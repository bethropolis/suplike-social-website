<?php
require "../../inc/dbh.inc.php";
require "../../inc/Auth/auth.php";
if (isset($_GET["chat"])) {
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
        $from =  $un_ravel->_getUser($_GET['from']);
        $to =  $un_ravel->_getUser($_GET['to']);
        $items = $conn->query("SELECT `chat`.*,`users`.`profile_picture`,`users`.`gender` FROM `chat` ,`users`  WHERE `chat`.`id`>" . $start . " AND (`chat`.`who_to`='$to' OR `chat`.`who_to`='$from')  AND (`chat`.`who_from`='$from' OR `chat`.`who_from`='$to') AND `users`.`idusers`='$from' ORDER BY `chat`.`time` LIMIT 15;");
        while ($row = $items->fetch_assoc()) {
            $row["who_from"] = $un_ravel->_queryUser($row["who_from"], 2);
            $row["who_to"] = $un_ravel->_queryUser($row["who_to"], 2);
            $result['items'][] = $row;
        }
        print_r(json_encode($result));
    }
}
