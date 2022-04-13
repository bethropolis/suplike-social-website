<?php
require '../r.php';
// comment on a post only
// Path: api\v1\comment\index.php
// Compare this snippet from ../../../inc/comment.inc.php
    if(isset($_POST['comment'])) {
        $post_id = $_POST['comment'];
        $user_id = $_POST['user_id'];
        $comment = $_POST['comment_text'];
        $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
        $result = $conn->query($sql);
        if($result) {
            die(json_encode(
                [
                    'code' => 0,
                    'msg' => "comment posted",
                    'type' => 'success'
                ]
            ));
        } else {
            die(json_encode(
                [
                    'code' => 1,
                    'msg' => "could not post comment",
                    'type' => 'error'
                ]
            ));
        }
}



?>