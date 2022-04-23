<?php

class Notification
{
    public $notify;
    public $notify_id;
    public $user;
    public $text;
    public $type;
    public $conn;
    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }
    public function _update_text($text)
    {
        $this->text = $text;
    }
    public function _update_type($type)
    {
        $this->type = $type;
    }
    public function _update_notify_id($notify_id)
    {
        $this->notify_id = $notify_id;
    }
    public function _update_notify($notify)
    {
        $this->notify = $notify;
    }
    public function _generate_id()
    {
        $id = bin2hex(openssl_random_pseudo_bytes(3));
        $this->notify_id = $id;
        $this->_update_notify_id($id);
    }
    public function _update_user($user)
    {
        $this->user = $user;
    }
    public function _update_all($user, $text, $type)
    {
        $this->_update_user($user);
        $this->_update_text($text);
        $this->_update_type($type);
        $this->_generate_id();
    }
    public function notify($user, $text,$type='note'){
        $this->_update_all($user, $text, $type);
        $this->_insert_notify();
    }

    public function _insert_notify()
    {
        $sql = "INSERT INTO notify (`notification_id`, `user`, `text`, `type`) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $error = new Err();
            $error->err(0, 'mysqli_stmt_prepare() failed', 'mysqli_stmt_prepare() failed');
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $this->notify_id, $this->user, $this->text, $this->type);
            mysqli_stmt_execute($stmt);
        }
    }

    # get users notification
    public function _get_notify($user)
    {
        $sql = "SELECT * FROM notify WHERE `user` = '$user' AND `seen` = 0";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $notify = array();
            while ($row = $result->fetch_assoc()) {
                $notify[] = $row;
            }
            print_r(json_encode(array('code' => 0, 'msg' => 'notification fetched', 'type' => 'success', 'data' => $notify)));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    public function _get_seen($user)
    {
        $sql = "SELECT * FROM notify WHERE `user` = '$user' AND `seen` = 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $notify = array();
            while ($row = $result->fetch_assoc()) {
                $notify[] = $row;
            }
            print_r(json_encode(array('code' => 0, 'msg' => 'notification fetched', 'type' => 'success', 'data' => $notify)));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    public function _delete_notify($notify_id)
    {
        $sql = "DELETE FROM notify WHERE `notification_id` = '$notify_id'";
        $result = $this->conn->query($sql);
        if ($result) {
            print_r(json_encode(array('code' => 0, 'msg' => 'notification deleted', 'type' => 'success')));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    # update seen status
    public function _update_seen($notify_id)
    {
        $sql = "UPDATE notify SET `seen` = '1' WHERE `notification_id` = '$notify_id'";
        $result = $this->conn->query($sql);
        if ($result) {
            print_r(json_encode(array('code' => 0, 'msg' => 'notification updated', 'type' => 'success')));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    public function _update_seenall($user){
        $sql = "UPDATE notify SET `seen` = '1' WHERE `user` = '$user'";
        $result = $this->conn->query($sql);
        if ($result) {
            print_r(json_encode(array('code' => 0, 'msg' => 'notification updated', 'type' => 'success')));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    public function _delete_all($user){
        $sql = "DELETE FROM notify WHERE `user` = '$user' AND `seen` = 0";
        $result = $this->conn->query($sql);
        if ($result) {
            print_r(json_encode(array('code' => 0, 'msg' => 'notification deleted', 'type' => 'success')));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    public function _get_type($type)
    {
        $sql = "SELECT * FROM notify WHERE `type` = '$type'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $notify = array();
            while ($row = $result->fetch_assoc()) {
                $notify[] = $row;
            }
            print_r(json_encode(array('code' => 0, 'msg' => 'notification fetched', 'type' => 'success', 'data' => $notify)));
        } else {
            print_r(json_encode(array('code' => 4, 'msg' => 'no notification found', 'type' => 'error')));
        }
    }
    public function _check_new($user)
    {
        // select notification less than 30 seconds ago
        $sql = "SELECT * FROM notify WHERE `user` = '$user' AND `seen` = 0 AND `date` > DATE_SUB(NOW(), INTERVAL 10 SECOND)"; 
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            print_r(json_encode(array('code' => 0, 'new' =>true)));
        } else {
            print_r(json_encode(array('code' => 4, 'new' => false)));
        }
    }
}