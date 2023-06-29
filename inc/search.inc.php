<?php
header('content-type: application/json');
require './dbh.inc.php';
require './Auth/auth.php';
require './errors/error.inc.php';

session_start();
if (isset($_GET['query'])) {
    $type = filter_input(INPUT_GET, 'type');
    $query = filter_input(INPUT_GET, 'query');
    $user = isset($_SESSION['token']) ? $un_ravel->_getUser($_SESSION['token'])  : null;

    if ($type === 'users') {
        $sql = "SELECT u.uidusers, u.usersFirstname, u.usersSecondname, u.profile_picture, u.bio,
        (CASE WHEN f.following IS NOT NULL THEN true ELSE false END) AS following
        FROM users u
        LEFT JOIN (
            SELECT user, following
            FROM following
            WHERE user = ?
        ) f ON u.idusers = f.following
        WHERE (u.uidusers LIKE ? OR u.usersFirstname LIKE ?)
        ORDER BY u.page_visit DESC
        LIMIT 20";

        if ($query == null) {
            $sql = "SELECT u.uidusers, u.usersFirstname, u.usersSecondname, u.profile_picture, u.bio,
                (CASE WHEN f.following IS NOT NULL THEN true ELSE false END) AS following, ak.token
            FROM users u
            LEFT JOIN (
                SELECT user, following
                FROM following
                WHERE user = ?
            ) f ON u.idusers = f.following
            LEFT JOIN auth_key ak ON u.idusers = ak.user
            WHERE u.idusers <> ?
            ORDER BY u.page_visit DESC
            LIMIT 5;
            ";
        }

        $stmt = $conn->prepare($sql);
        $likeQuery = "%$query%";
        $query != null ? $stmt->bind_param("iss", $user, $likeQuery, $likeQuery) : $stmt->bind_param("ii", $user, $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resultArray = [];
            while ($row = $result->fetch_assoc()) {
                $resultArray[] = $row;
            }
            print_r(json_encode([
                'code' => 1,
                'msg' => 'users fetched',
                'type' => 'success',
                'data' => $resultArray
            ]));
        } else {
            $error->err("Search", 21, "no users found");
        }
    } elseif ($type === 'posts') {
        if ($query == null) {
            $sql = "SELECT p.id, p.post_id, p.repost, p.image, p.image_text, p.userid, p.type, p.date_posted, p.post_likes, p.day, p.time, u.uidusers, u.profile_picture, u.usersFirstname, u.usersSecondname, 
            (CASE WHEN EXISTS (SELECT id FROM likes WHERE post_id = p.id AND user_id = ?) THEN true ELSE false END) AS liked 
            FROM posts p 
            INNER JOIN users u ON p.userid = u.idusers 
            WHERE p.deleted = false
            ORDER BY 
                (CASE WHEN p.post_likes IS NULL THEN 0 ELSE p.post_likes END) DESC, 
                p.time DESC 
            LIMIT 15";
        } else {
            $sql = "SELECT p.id, p.post_id, p.repost, p.image, p.image_text, p.userid, p.type, p.date_posted, p.post_likes, p.day, p.time, u.uidusers, u.profile_picture, u.usersFirstname, u.usersSecondname, 
            (CASE WHEN EXISTS (SELECT id FROM likes WHERE post_id = p.id AND user_id = ?) THEN true ELSE false END) AS liked 
            FROM posts p 
            INNER JOIN users u ON p.userid = u.idusers 
            WHERE p.image_text LIKE '%$query%' AND p.deleted = false
            ORDER BY p.time DESC LIMIT 15";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $result_array[] = array(
                    "id" => $row["id"],
                    "post_id" => $row["post_id"],
                    "repost" => $row["repost"],
                    "image" => $row["image"],
                    "image_text" => $row["image_text"],
                    "userid" => $row["userid"],
                    "type" => $row["type"],
                    "date_posted" => $row["date_posted"],
                    "post_likes" => $row["post_likes"],
                    "day" => $row["day"],
                    "time" => $row["time"],
                    "uidusers" => $row["uidusers"],
                    "profile_picture" => $row["profile_picture"],
                    "comments" => 0,
                    "user" => array(
                        "name" => $row["uidusers"],
                        "profile_picture" => $row["profile_picture"],
                    ),
                    "liked" => $row["liked"]
                );
            }
            print_r(
                json_encode(
                    [
                        'code' => 1,
                        'msg' => 'posts fetched',
                        'type' => 'success',
                        'data' => $result_array
                    ]
                )
            );
        } else {
            $error->err("Search", 22, "no posts found");
        }
    } else if ($type === 'post-tags') {
        // Fetch the posts that match the tag name
        $sql = "SELECT p.id, p.post_id, p.repost, p.image, p.image_text, p.userid, p.type, p.date_posted, p.post_likes, p.day, p.time,
                 u.idusers, u.uidusers, u.profile_picture, u.usersFirstname, u.usersSecondname,u.isAdmin, u.isBot,
        (CASE WHEN EXISTS (SELECT id FROM likes WHERE post_id = p.id AND user_id = ?) THEN true ELSE false END) AS liked,
        GROUP_CONCAT(t.name) AS tags,
        (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id) AS comment_count
    FROM posts p
    INNER JOIN users u ON p.userid = u.idusers
    INNER JOIN post_tags pt ON pt.post_id = p.id
    INNER JOIN tags t ON t.id = pt.tag_id
    WHERE t.name LIKE ? AND p.deleted = false
    GROUP BY p.id
    ORDER BY p.post_likes DESC
    LIMIT 15
    
";
        $stmt = $conn->prepare($sql);
        $query = "%$query%";
        $stmt->bind_param("ss", $user, $query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tags_query = "SELECT name FROM tags INNER JOIN post_tags ON post_tags.tag_id = tags.id WHERE post_tags.post_id = ?";
                $tags_stmt = $conn->prepare($tags_query);
                $tags_stmt->bind_param("i", $row['id']);
                $tags_stmt->execute();
                $tags_result = $tags_stmt->get_result();
                $tags = array();
                while ($tag_row = $tags_result->fetch_assoc()) {
                    array_push($tags, $tag_row['name']);
                }
                $name = $row["uidusers"];
                $profile_token = $un_ravel->_queryUser($row['idusers'], 4);
                $result_array[] = array(
                    "id" => $row["id"],
                    "post_id" => $row["post_id"],
                    "comments" => $row["comment_count"],
                    "repost" => $row["repost"],
                    "image" => $row["image"],
                    "image_text" => $row["image_text"],
                    "userid" => $row["userid"],
                    "type" => $row["type"],
                    "admin" => $row["isAdmin"],
                    "bot" => $row["isBot"],
                    "date_posted" => $row["date_posted"],
                    "post_likes" => $row["post_likes"],
                    "day" => $row["day"],
                    "time" => $row["time"],
                    "tags" => $tags,
                    "uidusers" => $row["uidusers"],
                    "profile_picture" => $row["profile_picture"],
                    "user" => array(
                        "name" => $name,
                        "id" => $profile_token,
                        "username" => $row["uidusers"]
                    ),
                    "liked" => $row["liked"]
                );
            }
            print_r(
                json_encode(
                    [
                        'code' => 1,
                        'msg' => 'posts fetched',
                        'type' => 'success',
                        'data' => $result_array
                    ]
                )
            );
        } else {
            $error->err("post", 23, "no posts found");
        }
    } else if ($type === 'tags') {
        // execute the SQL query
        $sql = "SELECT tags.name, COUNT(*) AS tag_count 
        FROM post_tags
        INNER JOIN tags ON post_tags.tag_id = tags.id
        GROUP BY tags.name
        ORDER BY tag_count DESC
        LIMIT 15";
        if ($query != "" || $query != null) {
            $sql = "SELECT tags.name, COUNT(*) AS tag_count 
            FROM post_tags
            INNER JOIN tags ON post_tags.tag_id = tags.id
            WHERE tags.name LIKE '%$query%'
            GROUP BY tags.name
            ORDER BY tag_count DESC
            LIMIT 15";
        }
        $result = mysqli_query($conn, $sql);

        // retrieve the results as an associative array
        $popular_tags = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $popular_tags[] = array(
                "name" => $row["name"],
                "count" => $row["tag_count"]
            );
        }
        print_r(
            json_encode(
                [
                    'code' => 1,
                    'msg' => 'tags fetched',
                    'type' => 'success',
                    'data' => $popular_tags
                ]
            )
        );
    } else {
        $error->err("Search", 21, "invalid search type");
    }
} else {
    $error->err("Search", 21, "search query missing");
}
