<?php
require_once '../r.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$un_ravel->_isValid(filter_input(INPUT_POST, 'user_token'))) {
        $error->err("invalid token", 1, "Invalid user token");
    }

    $user = $un_ravel->_getUser(filter_input(INPUT_POST, 'user_token'));

    if (isset($_POST['id'])) {
        $id = filter_input(INPUT_POST, 'id');
        $type =  filter_input(INPUT_POST, 'type') == 'comment' ? 1 : 0;
        if ($id) {
            $sql = "INSERT INTO reports
                 (post_id,is_comment) VALUES (?,?)";
            if ($type) {
                $sql = "INSERT INTO reports
                 (comment_id,is_comment) VALUES (?,?)";
            }
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id, $type);
            $result = $stmt->execute();
            if ($result) {
                die(json_encode(
                    [
                        'status' => 'success',
                        'msg' => 'reported',
                        'id' => $id
                    ]
                ));
            } else {
                $error->err("API access", 2, "Failed to report post");
            }
        } else {
            $error->err("API access", 3, "Invalid post ID");
        }
    }

    if ($un_ravel->_isAdmin($user)) {
        if (isset($_POST['del'])) {
            $id = filter_input(INPUT_POST, 'del');
            if ($id) {
                $sql = "UPDATE `reports` SET `delt`=TRUE WHERE `post_id`=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();

                $sql = "DELETE FROM `posts` WHERE `id`=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();

                die(json_encode(
                    [
                        'status' => 'success',
                        'msg' => 'Post deleted'
                    ]
                ));
            } else {
                die(json_encode(
                    [
                        'status' => 'error',
                        'msg' => 'Invalid post ID'
                    ]
                ));
            }
        }
    }
}
