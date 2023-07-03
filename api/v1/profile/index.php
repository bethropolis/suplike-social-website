<?php
require '../r.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $u = $_GET['user_token'];
    $user = $un_ravel->_userid($username);
    $from = $un_ravel->_getUser($u);

    $query = "SELECT u.uidusers, u.usersFirstname,u.profile_picture, u.usersSecondname, u.emailusers, u.gender, u.bio, u.date_joined, ak.token, ak.chat_auth,
              (SELECT COUNT(*) FROM following WHERE following = u.idusers) AS followers,
              (SELECT COUNT(*) FROM following WHERE user = u.idusers) AS following,
              (SELECT COUNT(*) FROM posts WHERE userid = u.idusers) AS posts
              FROM users u
              JOIN auth_key ak ON u.idusers = ak.user
              WHERE u.idusers = $user";

    $postQuery = "SELECT
    posts.*,
    users.uidusers,
    users.profile_picture,
    GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') AS tags,
    COALESCE(comment_counts.comment_count, 0) AS comment_count
  FROM
    posts
    INNER JOIN users ON posts.userid = users.idusers
    LEFT JOIN post_tags ON posts.id = post_tags.post_id
    LEFT JOIN tags ON post_tags.tag_id = tags.id
    LEFT JOIN (
      SELECT post_id, COUNT(*) AS comment_count
      FROM comments
      GROUP BY post_id
    ) AS comment_counts ON posts.post_id = comment_counts.post_id
  WHERE
    posts.userid = $user AND posts.deleted = false
  GROUP BY
    posts.id
  ORDER BY
    posts.id DESC
  LIMIT 25";
    $result = mysqli_query($conn, $query);

    $postResults = mysqli_query($conn, $postQuery);
    $posts = [];
    while ($row = mysqli_fetch_assoc($postResults)) {
        $sql = "SELECT id
        FROM likes WHERE user_id = '$from' AND post_id = '$row[id]';
        ";
        $likes_result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($likes_result) === 1) {
            $row['liked'] = true;
        } else {
            $row['liked'] = false;
        }
        $row['user'] = ['name' => $row['uidusers'], 'profile_picture' => $row["profile_picture"]];
        $posts[] = $row;
    }

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $profile = [
            'username' => $row['uidusers'],
            'name' => $row['usersFirstname'] . ' ' . $row['usersSecondname'],
            'picture' => BASE_URL . "/{$row['profile_picture']}",
            'token' => $row['token'],
            'isFollowing' => $un_ravel->_isFollower($user, $from),
            'followers' => $row['followers'],
            'following' => $row['following'],
            'post' => $row['posts'],
            'chat_key' => $row['chat_auth'],
            'gender' => $row['gender'],
            'bio' => $row['bio'],
            'date_joined' => $row['date_joined'],
            'posts' => $posts
        ];
        echo json_encode($profile);
    } else {
        echo "User not found.";
    }
}
