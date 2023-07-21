<?php


function commentNotify($comment,  $post_id, $comment_id)
{
    global $un_ravel;
    // Regular expression to find user mentions in the comment
    preg_match_all('/@(\w+)/', $comment, $matches);
    if (!empty($matches[1])) {
        $mentioned_users = $matches[1];
        $notification = new Notification();
        foreach ($mentioned_users as $mentioned_user) {
            // Check if the mentioned user exists
            $user_id = $un_ravel->_userid($mentioned_user);
            if ($user_id !== null) {
                // Send notification to the mentioned user
                $notification_text = "You were mentioned in a comment on a <a href='comment.php?id=$post_id&comment={$comment_id}#comment-{$comment_id}'>post</a>.";
                $notification->notify($user_id, $notification_text, 'mention');
            }
        }
    }
}
