<?php
require_once '../r.php';

header("Content-Type: application/json; charset=UTF-8");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

if(isset($_POST['comment'])) {
    $post_id = filter_input(INPUT_POST, 'post_id');
    $user_token = filter_input(INPUT_POST, 'user_token');
    $user_ =$un_ravel->_getUser($user_token);
    $user_id = $un_ravel->_username($user_);
    $comment = filter_input(INPUT_POST, 'comment');
    $parent_comment_id = isset($_POST['parent_id']) ? filter_input(INPUT_POST, 'parent_id') : null; // get parent comment ID if it exists


    // authenticate user
    authentication_check($user_);

    
    // Prepare and execute SQL statement to insert comment into database
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user, user_token, comment, parent_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $post_id, $user_id, $user_token, $comment, $parent_comment_id);
    $result = $stmt->execute();

    // Return appropriate response based on success or failure of SQL statement
    if($result) {
        die(json_encode(
            [
                'code' => 0,
                'msg' => "Comment posted successfully",
                'type' => 'success'
            ]
        ));
    } else {
        die(json_encode(
            [
                'code' => 1,
                'msg' => "Could not post comment",
                'type' => 'error'
            ]
        ));
    }
}

// Check if post_id parameter is set to retrieve comments
if(isset($_GET['post_id'])) {
    $post_id = filter_input(INPUT_GET, 'post_id');
    // Prepare and execute SQL statement to retrieve comments from database

    $stmt = $conn->prepare("SELECT comments.id, comments.user, comments.comment, comments.likes, comments.date, comments.parent_id, 
            users.usersFirstname, users.usersSecondname, CONCAT('".BASE_URL."',users.profile_picture) as avatar
            FROM comments
            JOIN users ON comments.user = users.uidusers
            WHERE comments.post_id = ?
            ORDER BY comments.id ASC");
    $stmt->bind_param("s", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return appropriate response based on success or failure of SQL statement
    if ($result->num_rows > 0) {
        $comments = array();
        $temp_comments = array();

        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $username = $row['user'];
            $name = $row['usersFirstname'] . ' ' . $row['usersSecondname'];
            $avatar = $row['avatar'];
            $comment = $row['comment'];
            $likes = $row['likes'];
            $date = $row['date'];
            $parent_id = $row['parent_id'] ?? null;

            $temp_comments[$id] = array(
                'id' => $id,
                'username' => $username,
                'name' => $name,
                'avatar' => $avatar,
                'comment' => $comment,
                'likes' => $likes,
                'parent_id' => $parent_id,
                'date' => $date,
                'replies' => array()
            );

            if($parent_id != null && $parent_id != 0) {
                $temp_comments[$parent_id]['replies'][] = &$temp_comments[$id];
            } else {
                $comments[] = &$temp_comments[$id];
            }
        }

        $response = array(
            'code' => 0,
            'msg' => 'Comments retrieved successfully',
            'type' => 'success',
            'data' => $comments
        );

        echo json_encode($response);
    } else {
        $response = array(
            'code' => 1,
            'msg' => 'No comments found',
            'type' => 'error'
        );

        echo json_encode($response);
    }
} else {
    $response = array(
        'code' => 2,
        'msg' => 'Missing post_id parameter',
        'type' => 'error'
    );

    echo json_encode($response);
}