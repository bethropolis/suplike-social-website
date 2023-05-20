<?php
require "../r.php";

if (isset($_GET['user_token'])) {
    $type = $_GET['type'];
    $user = $un_ravel->_getUser($_GET["user_token"]);

    // build the SQL query based on the type parameter
    if ($type == "all") {
        $sql = "SELECT * FROM notify WHERE user = $user";
    } else if ($type == "seen") {
        $sql = "SELECT * FROM notify WHERE user = $user AND seen = 1";
    } else if ($type == "unseen") {
        $sql = "SELECT * FROM notify WHERE user = $user AND seen = 0";
    } else if ($type == "mark") {
        $notification_id= $_GET['id'];
        $sql = "UPDATE notify SET seen = 1 WHERE user = $user AND notification_id = '$notification_id'";
        // execute the query
        $result = $conn->query($sql);

        // check if the query was successful
        if ($result === true) {
            echo json_encode(array("message" => "Notification marked as seen"));
        } else {
            echo json_encode(array("error" => "Failed to mark notification as seen"));
        }
        exit;
    }else if ($type == "delete") {
        $notification_id= $_GET['id'];
        $sql = "DELETE FROM notify WHERE notification_id = '$notification_id' AND user = '$user'";
        $result = $conn->query($sql);
        if (!$result) {
            echo json_encode(array('success' => false, 'message' => 'Error deleting notification.'));
            exit();
        } else {
            echo json_encode(array('success' => true, 'message' => 'Notification deleted.'));
            exit();
        }
    }  else {
        echo json_encode(array("error" => "Invalid type parameter"));
        exit;
    }

    // execute the query and get the results
    $result = $conn->query($sql);

    // check if there are any results
    if ($result->num_rows > 0) {
        $notifications = array();
        while ($row = $result->fetch_assoc()) {
            array_push($notifications, $row);
        }
        echo json_encode($notifications);
    } else {
        echo json_encode(array("message" => "No notifications found"));
    }
}